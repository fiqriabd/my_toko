<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukController extends Controller
{
    public function index()
    {
        $kategori = Kategori::orderBy('nama_kategori','asc')
                    ->pluck('nama_kategori','id_kategori');
        return view('produk.index', compact('kategori'));
    }

    public function data()
    {
        $produk = Produk::leftJoin('kategori','kategori.id_kategori','produk.id_kategori')
            ->select('produk.*', 'nama_kategori')
            ->orderBy('kode_produk', 'asc')->get();

        return datatables()
        ->of($produk)
        ->addIndexColumn()
        ->addColumn('select_all', function ($produk){
            return '
                <input type="checkbox" name="id_produk[]" value="'. $produk->id_produk .'">
            ';
        })
        ->addColumn('kode_produk', function ($produk){
            return '<span class="label label-success">'. $produk->kode_produk .'</span>';
        })
        ->addColumn('harga_beli_produk', function ($produk){
            return format_uang($produk->harga_beli_produk);
        })
        ->addColumn('harga_jual_produk', function ($produk){
            return format_uang($produk->harga_jual_produk);
        })
        ->addColumn('stok_produk', function ($produk){
            return format_uang($produk->stok_produk);
        })
        ->addColumn('aksi', function ($produk) {
            return '
            <div class="btn-group">
                <button onclick="editForm(`'.route('produk.update', $produk->id_produk).'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i> Edit</button>
                <button onclick="deleteData(`'.route('produk.destroy', $produk->id_produk).'`)"class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i> Hapus</button>
            </div>
                ';
        })
        ->rawColumns(['aksi', 'kode_produk', 'select_all'])
        ->make(true);
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // $produk = Produk::latest()->first();
        // $request['kode_produk'] = 'P'. tambah_nol_didepan((int)$produk->id_produk +1, 6);
        // $produk = Produk::create($request->all());
        $produkTerakhir = Produk::orderBy('id_produk', 'desc')->first();

        $id = $produkTerakhir ? $produkTerakhir->id_produk + 1 : 1;

        $request['kode_produk'] = 'P' . tambah_nol_didepan($id, 6);

        Produk::create($request->all());

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
        $produk->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    public function destroy(string $id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return response()->json(null,204);
    }

    public function deleteSelected(Request $request){

        foreach ($request->id_produk as $id) {
            $produk = Produk::find($id);
            $produk->delete();
        }

        return response()->json(null,204);
    }
}
