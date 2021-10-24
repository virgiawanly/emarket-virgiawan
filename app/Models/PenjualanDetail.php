<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';

    protected $fillable = ['penjualan_id', 'barang_id', 'harga_jual', 'diskon', 'jumlah', 'sub_total'];

    public function barang(){
        return $this->belongsTo(Barang::class);
    }

    public function penjualan(){
        return $this->belongsTo(Penjualan::class);
    }
}
