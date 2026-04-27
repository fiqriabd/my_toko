<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('pengaturan.index');
    }

    public function show()
    {
        return Pengaturan::first();
    }

    public function update(Request $request)
    {
        try{
        $pengaturan = Pengaturan::firstOrNew();

        if (!$pengaturan) {
            return response()->json('Data pengaturan tidak ditemukan', 500);
        }

        $pengaturan->nama_perusahaan = $request->nama_perusahaan;
        $pengaturan->telepon = $request->telepon;
        $pengaturan->alamat = $request->alamat;
        $pengaturan->tipe_nota = $request->tipe_nota;

        if ($request->hasFile('path_logo')) {
            $file = $request->file('path_logo');
            $nama = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/imagez'), $nama);

            $pengaturan->path_logo = "imagez/$nama";
        }

        $pengaturan->save();

        return response()->json('Data berhasil disimpan', 200);
    } catch (\Exception $e) {
        return response()->json($e->getMessage(), 500);
    }
}
}
