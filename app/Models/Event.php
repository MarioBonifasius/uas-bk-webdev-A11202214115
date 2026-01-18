<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul',
        'deskripsi',
        'tanggal',
        'lokasi',
        'gambar',
    ];

    protected $casts = [
        'tanggal' => 'date',
        // atau
        // 'tanggal' => 'datetime',
    ];




    public function Kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tikets()
    {
        return $this->hasMany(Tiket::class);
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
