<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = ['no_faktur', 'tgl_faktur', 'total_bayar', 'pelanggan_id', 'user_id'];

    public function detail(){
        return $this->hasMany(PenjualanDetail::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class);
    }

    public function tampung_bayar(){
        return $this->hasOne(TampungBayar::class);
    }

    public static function buat_no_faktur(){
        $no_urut = self::selectRaw("IFNULL(MAX(SUBSTRING(`no_faktur`,10,5)),0) + 1 AS no_urut")->whereRaw("SUBSTRING(`no_faktur`,2,4) = '" . date('Y') . "'")->whereRaw("SUBSTRING(`no_faktur`,6,2) = '" . date('m') . "'")->orderBy('no_urut')->first()->no_urut;
        $no_faktur = "O" . date("Ymd") . sprintf("%'.05d", $no_urut);
        return $no_faktur;
    }

}
