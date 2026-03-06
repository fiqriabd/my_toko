<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    public function index(){
        return view('pembelian_detail.index');
    }
}
