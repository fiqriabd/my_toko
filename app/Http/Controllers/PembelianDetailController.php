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
            return '<input type="number" class="form-control input-sm quantity" name="jumlah_'. $detail->id_pembelian_detail .'" value="'. $detail->jumlah_pembelian_detail .'">';
        })
        ->addColumn('subtotal_pembelian_detail', function ($detail) {
            return 'Rp. '. $detail -> subtotal_pembelian_detail;
        })        
        ->addColumn('aksi', function ($detail) {
            return '
            <div class="btn-group">
                <button onclick="deleteData(`'.route('pembelian_detail.destroy', $detail->id_pembelian_detail).'`)"class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
            </div>
                ';
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

    public function destroy($id)
    {
        $detail = PembelianDetail::find($id);
        $detail->delete();

        return response(null, 204);
    } 
}
