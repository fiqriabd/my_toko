<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = "pembelian";
    protected $primaryKey = 'id_pembelian';
    protected $guarded = [];
    
    public function distributor()
    {
        return $this->belongsTo(Distributor::class,'id_distributor','id_distributor');
    }
}

