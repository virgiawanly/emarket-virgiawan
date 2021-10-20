<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = ['nama_produk'];

    public function barang(){
        return $this->hasMany(Barang::class);
    }

    /**
     * Cek apakah produk tersebut punya barang atau tidak
     *
     * @return boolean
     */
    public function canDelete(){
        return !$this->barang()->exists();
    }
}
