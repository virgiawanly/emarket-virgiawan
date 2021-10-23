<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use HasFactory;

    protected $table = 'pemasok';

    protected $fillable = ['kode_pemasok', 'nama', 'alamat', 'kota', 'no_telp'];

    public function pembelian()
    {
        return $this->hasMany(Pembelian::class);
    }

    /**
     * Cek apakah pemasok tersebut bisa dihapus atau tidak
     *
     * @return boolean
     */
    public function canDelete()
    {
        return !$this->pembelian()->exists();
    }

    /**
     * Menggenerate kode pemasok berdasarkan kode pemasok sebelumnya
     * @return string $kode_pemasok
     */
    public static function buat_kode_pemasok()
    {
        $pemasok = self::latest()->first() ?? new self();
        return 'S' . sprintf("%0" . 6 . "s", (int) $pemasok->id + 1);
    }
}
