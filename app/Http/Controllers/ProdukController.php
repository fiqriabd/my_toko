<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all()->pluck('nama','id_kategori');
        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Produk::orderBy('id_produk', 'asc')->get();
        return datatables()
        ->of($produk)
        ->addIndexColumn()
        ->addColumn('aksi', function ($produk) {
            return '
            <div class="btn-group">
                <button onclick="editForm(`'.route('produk.update', $produk->id_produk).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i> Edit</button>
                <button onclick="deleteData(`'.route('produk.destroy', $produk->id_produk).'`)"class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
            </div>
                ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $produk = Produk::latest()->first();
        $request['kode_produk'] = 'P-'. tambah_nol_didepan($produk->id_produk);
        $produk = Produk::create($request->all());
         
        return response()->json('Data berhasil disimpan', 200);
    }

    public function show(string $id)
    {
        $produk = Produk::find($id);

        return response()->json($produk);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $produk = Produk::find($id);
        $produk->nama = $request->nama_produk;
        $produk->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy(string $id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return response()->json(null,204);
    }
}
