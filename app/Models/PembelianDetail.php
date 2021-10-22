<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'detail_pembelian';

    protected $fillable= ['pembelian_id', 'barang_id', 'harga_beli', 'jumlah', 'sub_total'];

    public function barang(){
        return $this->belongsTo(Barang::class);
    }

    public function pembelian(){
        return $this->belongsTo(Pembelian::class);
    }
}
