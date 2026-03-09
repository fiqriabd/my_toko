<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Models\Produk;

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
}
