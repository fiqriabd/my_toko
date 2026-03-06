<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;

class PengeluaranController extends Controller
{
    public function index()
    {
        return view('pengeluaran.index');
    }

    public function data()
    {
        $pengeluaran = Pengeluaran::orderBy('id_pengeluaran', 'asc')->get();
        return datatables()
        ->of($pengeluaran)
        ->addIndexColumn()
        ->addColumn('created_at', function ($pengeluaran) {
            return tanggal_indonesia($pengeluaran->created_at, false);
        })
        ->addColumn('nominal_pengeluaran', function ($pengeluaran) {
            return format_uang($pengeluaran->nominal_pengeluaran);
        })        
        ->addColumn('aksi', function ($pengeluaran) {
            return '
            <div class="btn-group">
                <button onclick="editForm(`'.route('pengeluaran.update', $pengeluaran->id_pengeluaran).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i> Edit</button>
                <button onclick="deleteData(`'.route('pengeluaran.destroy', $pengeluaran->id_pengeluaran).'`)"class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
            </div>
                ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function store(Request $request)
    {
        $pengeluaran = Pengeluaran::create($request->all());

        return response()->json("Data berhasil disimpan", 200);
    }

    public function show(string $id)
    {
        $pengeluaran = Pengeluaran::find($id);

        return response()->json($pengeluaran);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $pengeluaran = Pengeluaran::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy(string $id)
    {
        $pengeluaran = Pengeluaran::find($id);
        $pengeluaran->delete();

        return response()->json(null,204);
    }
}

