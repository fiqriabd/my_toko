<?php

namespace App\Http\Controllers;

use App\Models\PembelianDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Distributor;

class PembelianController extends Controller
{
    public function index()
    {
        $distributor = Distributor::orderBy('nama_distributor')->get();
        return view('pembelian.index', compact('distributor'));
    }

    public function data()
    {
        $pembelian = Pembelian::with('distributor')
                    ->orderBy('id_pembelian', 'desc')
                    ->get();

        return datatables()
            ->of($pembelian)
            ->addIndexColumn()
            ->addColumn('total_item_pembelian', function ($pembelian) {
                return format_uang($pembelian->total_item_pembelian);
            })
            ->addColumn('total_harga_pembelian', function ($pembelian) {
                return 'Rp. '. format_uang($pembelian->total_harga_pembelian);
            })
            ->addColumn('bayar_pembelian', function ($pembelian) {
                return 'Rp. '. format_uang($pembelian->bayar_pembelian);
            })
            ->addColumn('tanggal', function ($pembelian) {
                return tanggal_indonesia($pembelian->created_at, false);
            })
            ->addColumn('distributor', function ($pembelian) {
                return $pembelian->distributor->nama_distributor ?? '-' ;
            })
            ->editColumn('diskon', function ($pembelian) {
                return $pembelian->diskon_pembelian . '%';
            })
            ->addColumn('aksi', function ($pembelian) {
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('pembelian.show', $pembelian->id_pembelian) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('pembelian.destroy', $pembelian->id_pembelian) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create($id)
    {
        $pembelian = new Pembelian();
        $pembelian->id_distributor              = $id;
        $pembelian->total_item_pembelian        = 0;
        $pembelian->total_harga_pembelian       = 0;
        $pembelian->diskon_pembelian            = 0;
        $pembelian->bayar_pembelian             = 0;
        $pembelian->save();

        session(['id_pembelian'=> $pembelian->id_pembelian]);
        session(['id_distributor' => $pembelian->id_distributor]);

        return redirect()->route('pembelian_detail.index');
    }

    public function store(Request $request)
    {
        $pembelian = Pembelian::findOrFail($request->id_pembelian);
        $pembelian->total_item_pembelian = $request->total_item;
        $pembelian->total_harga_pembelian = $request->total;
        $pembelian->diskon_pembelian = $request->diskon_pembelian;
        $pembelian->bayar_pembelian = $request->bayar_pembelian;
        $pembelian->update();

        $detail = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();
        
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk -> stok_produk += $item->jumlah_pembelian_detail;
            $produk -> update();
        }

        return redirect()->route('pembelian.index');
    }

    public function show($id)
    {
        $detail = PembelianDetail::with('produk')->where('id_pembelian', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_beli_pembelian_detail', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_beli_pembelian_detail);
            })
            ->addColumn('jumlah_pembelian_detail', function ($detail) {
                return format_uang($detail->jumlah_pembelian_detail);
            })
            ->addColumn('subtotal_pembelian_detail', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal_pembelian_detail);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $pembelian = Pembelian::find($id);
        $detail    = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();
        foreach ($detail as $item) {
            
            $item->delete();
        }

        $pembelian->delete();

        return response(null, 204);
    }
}
