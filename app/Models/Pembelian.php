<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $fillable = ['kode_masuk', 'tanggal_masuk', 'total', 'pemasok_id', 'user_id'];

    public function pemasok(){
        return $this->belongsTo(Pemasok::class);
    }

    public function detail(){
        return $this->hasMany(PembelianDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Membuat kode pembelian
     *
     * @return string $kode_masuk
     */
    public static function buat_kode_pembelian()
    {
        $no_urut = self::selectRaw("IFNULL(MAX(SUBSTRING(`kode_masuk`,10,5)),0) + 1 AS no_urut")->whereRaw("SUBSTRING(`kode_masuk`,2,4) = '" . date('Y') . "'")->whereRaw("SUBSTRING(`kode_masuk`,6,2) = '" . date('m') . "'")->orderBy('no_urut')->first()->no_urut;
        $kode_masuk = "I" . date("Ymd") . sprintf("%'.05d", $no_urut);
        return $kode_masuk;
    }
}
