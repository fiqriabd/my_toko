<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\PembelianDetail;
use function PHPUnit\Framework\returnArgument;

class PembelianDetailController extends Controller
{
    public function index()
    {
        $id_pembelian = session('id_pembelian');
        $produk = Produk::orderBy('nama_produk')->get();
        $distributor = Distributor::find(session('id_distributor'));
        if (! $distributor) {
            abort(404);
        }

        return view('pembelian_detail.index', compact('id_pembelian', 'produk', 'distributor'));
    }

    public function data($id)
    {
        $detail = PembelianDetail::with('produk')
            ->where('id_pembelian', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;
        
        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">'. $item->produk['kode_produk'] . '<span>';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_beli_pembelian_detail'] = 'Rp. '. $item -> harga_beli_pembelian_detail;
            $row['jumlah_pembelian_detail'] = '<input type="number" class="form-control input-sm quantity" data-id="'. $item->id_pembelian_detail .'" value="'. $item->jumlah_pembelian_detail .'">';
            $row['subtotal_pembelian_detail'] = $item->subtotal_pembelian_detail;
            $row['aksi'] = '<div class="btn-group">
                            <button onclick="deleteData(`'.route('pembelian_detail.destroy', $item->id_pembelian_detail).'`)"class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
                            </div>';
            $data[] = $row;

            $total += $item->harga_beli_pembelian_detail * $item->jumlah_pembelian_detail;
            $total_item += $item->jumlah_pembelian_detail;
        }
        $data[] = [
            '<div class="total hide">'. $total .'</div> <div class="total_item hide">'. $total .'<div>',
            '',
            '',
            '',
            '',
            '',
            ''
        ];


        return datatables()
        ->of($detail)
        ->addIndexColumn()
        ->addColumn('nama_produk', function ($detail) {
            return $detail->produk['nama_produk'];
        })
        ->addColumn('kode_produk', function ($detail) {
            return '<span class="label label-success">'. $detail->produk['kode_produk'] . '<span>';
        })
        ->addColumn('harga_beli_pembelian_detail', function ($detail) {
            return 'Rp. '. $detail -> harga_beli_pembelian_detail;
        })
        ->addColumn('jumlah_pembelian_detail', function ($detail){
            return '<input type="number" class="form-control input-sm quantity" data-id="'. $detail->id_pembelian_detail .'" value="'. $detail->jumlah_pembelian_detail .'">';
        })
        ->addColumn('subtotal_pembelian_detail', function ($detail) {
            return 'Rp. '. $detail -> subtotal_pembelian_detail;
        })        
        ->addColumn('aksi', function ($detail) {
            return 
            '<div class="btn-group">
                <button onclick="deleteData(`'.route('pembelian_detail.destroy', $detail->id_pembelian_detail).'`)"class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
            </div>';
        })
        ->rawColumns(['aksi', 'kode_produk', 'jumlah_pembelian_detail'])
        ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('id_produk', $request->id_produk)->first();
        if (! $produk) {
            return response()->json("Data gagal disimpan", 400);
        }
        $detail = new PembelianDetail();
        $detail->id_pembelian = $request->id_pembelian;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_beli_pembelian_detail = $produk->harga_beli_produk;
        $detail->jumlah_pembelian_detail = 1;
        $detail->subtotal_pembelian_detail = $produk->harga_beli_produk;
        $detail->save();

        return response()->json("Data berhasil disimpan", 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PembelianDetail::find($id);
        $detail->jumlah_pembelian_detail = $request->jumlah_pembelian_detail;
        $detail->subtotal_pembelian_detail = $detail->harga_beli_pembelian_detail * $request->jumlah_pembelian_detail;
        $detail->update();
    }
    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);
    } 
}
