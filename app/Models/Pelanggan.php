<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = ['kode_pelanggan', 'nama', 'alamat', 'no_telp', 'email'];

     /**
     * Menggenerate kode pelanggan berdasarkan kode pelanggan sebelumnya
     * @return string $kode_pelanggan
     */
    public static function buat_kode_pelanggan()
    {
        $pelanggan = self::latest()->first() ?? new self();
        return 'C' . sprintf("%0". 6 . "s", (int) $pelanggan->id + 1);
    }
}
