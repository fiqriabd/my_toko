<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Pengaturan;
use App\Models\Produk;
use Illuminate\Http\Request;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
        $penjualan = Penjualan::orderBy('id_penjualan', 'desc')->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_item_penjualan', function ($penjualan){
                return format_uang($penjualan->total_item_penjualan);
            })
            ->addColumn('total_harga_penjualan', function ($penjualan){
                return 'Rp. '. format_uang($penjualan->total_harga_penjualan);
            })
            ->addColumn('bayar_penjualan', function ($penjualan){
                return 'Rp. '. format_uang($penjualan->bayar_penjualan);
            })
            ->addColumn('tanggal', function ($penjualan){
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->editColumn('kasir', function ($penjualan){
                return $penjualan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan){
                return '
                <div class="btn-group">
                    <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);

    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->total_item_penjualan = 0;
        $penjualan->total_harga_penjualan = 0;
        $penjualan->bayar_penjualan = 0;
        $penjualan->diterima_penjualan = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan->total_item_penjualan = $request->total_item;
        $penjualan->total_harga_penjualan = $request->total;
        $penjualan->bayar_penjualan = $request->bayar_penjualan;
        $penjualan->diterima_penjualan = $request->diterima_penjualan;
        $penjualan->update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {

            $produk = Produk::find($item->id_produk);
            $produk->stok_produk -= $item->jumlah;
            $produk->update();
        }

        return redirect()->route('transaksi.selesai');
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual_penjualan_detail', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_jual_penjualan_detail);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $pengaturan = Pengaturan::first();

        return view('penjualan.selesai', compact('pengaturan'));
    }

    public function notaKecil()
    {
        $pengaturan = Pengaturan::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();
        
        return view('penjualan.nota_kecil', compact('pengaturan', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $pengaturan = Pengaturan::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('pengaturan', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }
}
