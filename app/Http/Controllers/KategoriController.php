<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori.index');
    }

    public function data()
    {
        $kategori = Kategori::orderBy('id_kategori', 'asc')->get();
        return datatables()
        ->of($kategori)
        ->addIndexColumn()
        ->addColumn('aksi', function ($kategori) {
            return '
            <div class="btn-group">
                <button onclick="editForm(`'.route('kategori.update', $kategori->id_kategori).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i> Edit</button>
                <button onclick="deleteData(`'.route('kategori.destroy', $kategori->id_kategori).'`)"class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
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
        $kategori = new Kategori();
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function show(string $id)
    {
        $kategori = Kategori::find($id);

        return response()->json($kategori);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $kategori = Kategori::find($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->update();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy(string $id)
    {
        $kategori = Kategori::find($id);
        $kategori->delete();

        return response()->json(null,204);
    }
}
