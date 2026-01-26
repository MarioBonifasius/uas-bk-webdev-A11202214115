<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'event_id',
        'order_date',
        'total_harga',
        'payment_status',
        'payment_method'
    ];
    protected $casts = [
        'order_date' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class);
    }
    public function tikets()
    {
        return $this->belongsToMany(Tiket::class, 'detail_orders')->withPivot(
            'jumlah',
            'subtotal_harga'
        );
    }
}
