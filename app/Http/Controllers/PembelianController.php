<?php

namespace App\Http\Controllers;

use App\Models\PembelianDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Distributor;

class PembelianController extends Controller
{
    public function index(){
        $distributor = Distributor::orderBy('nama_distributor')->get();
        return view('pembelian.index', compact('distributor'));
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
        $pembelian->total_item_pembelian = $request->total_item_pembelian;
        $pembelian->total_harga_pembelian = $request->total;
        $pembelian->diskon_pembelian = $request->diskon_pembelian;
        $pembelian->bayar_pembelian = $request->bayar_pembelian;
        $pembelian->update();

        dd($request->all());
        $detail = PembelianDetail::where('id_pembelian', $pembelian->id_pembelian)->get();
        
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            $produk -> stok_produk += $item->jumlah_pembelian_detail;
            $produk -> update();
        }

        return redirect()->route('pembelian.index');
    }
}
