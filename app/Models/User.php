<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function get_photo()
    {
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }
        else if ($this->photo && Storage::exists($this->photo)) {
            return Storage::url($this->photo);
        }
        else {
            return asset('img/default-avatar.png');
        }
    }

    public function penjualan(){
        return $this->hasMany(Penjualan::class);
    }

    public function pembelian(){
        return $this->hasMany(Pembelian::class);
    }

    public function canDelete(){
        return !$this->pembelian()->exists() && !$this->penjualan()->exists();
    }
}
