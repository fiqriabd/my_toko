<?php

namespace App\Http\Controllers;

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
}
