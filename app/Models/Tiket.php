<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tiket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'event_id',
        'tipe',
        'harga',
        'stok'
    ];

    public function detailorders()
    {
        return $this->hasMany(DetailOrder::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'detail_orders')->withPivot('jumlah', 'subtotal_harga');
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
