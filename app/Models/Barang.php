<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = ['kode_barang', 'produk_id', 'nama_barang', 'satuan', 'diskon', 'harga_jual', 'stok'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pembelian(){
        return $this->hasMany(PembelianDetail::class);
    }

    public function penjualan(){
        return $this->hasMany(PenjualanDetail::class);
    }

    /**
     * Menggenerate kode barang berdasarkan kode barang sebelumnya
     * @return string $kode_barang
     */
    public static function buat_kode_barang()
    {
        $barang = self::latest()->first() ?? new self();
        return 'B' . sprintf("%0". 8 . "s", (int) $barang->id + 1);
    }

    public function canDelete(){
        return !$this->pembelian()->exists() && !$this->penjualan()->exists();
    }
}
