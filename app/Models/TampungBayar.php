<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TampungBayar extends Model
{
    use HasFactory;

    protected $table = 'tampung_bayar';

    protected $fillable = ['penjualan_id', 'total', 'terima', 'kembali'];

    public function penjualan(){
        return $this->belongsTo(Penjualan::class);
    }
}
