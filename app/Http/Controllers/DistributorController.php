<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distributor;

class DistributorController extends Controller
{
    public function index()
    {
        return view('distributor.index');
    }

    public function data()
    {
        $distributor = Distributor::orderBy('nama_distributor', 'asc')->get();
        return datatables()
        ->of($distributor)
        ->addIndexColumn()
        ->addColumn('aksi', function ($distributor) {
            return '
            <div class="btn-group">
                <button onclick="editForm(`'.route('distributor.update', $distributor->id_distributor).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i> Edit</button>
                <button onclick="deleteData(`'.route('distributor.destroy', $distributor->id_distributor).'`)"class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
            </div>
                ';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function store(Request $request)
    {
        $distributor = Distributor::create($request->all());

        return response()->json("Data berhasil disimpan", 200);
    }

    public function show(string $id)
    {
        $distributor = Distributor::find($id);

        return response()->json($distributor);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $distributor = Distributor::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy(string $id)
    {
        $distributor = Distributor::find($id);
        $distributor->delete();

        return response()->json(null,204);
    }
}
