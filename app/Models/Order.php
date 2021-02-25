<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    
    const NOT_SELECTED_PAYMENT = 'not_selected_payment';
    const WAITING_FOR_THE_TRANSFER = 'waiting_for_the_transfer';
    const PAID = 'paid';
    const SEND_BEFORE_PAID = 'send_before_paid';
    const CANCELLED = 'cancelled';

    const orderStatuses = [
        0 => self::NOT_SELECTED_PAYMENT,
        1 => self::WAITING_FOR_THE_TRANSFER,
        2 => self::PAID,
        3 => self::SEND_BEFORE_PAID,
        4 => self::CANCELLED,
    ];

    protected $fillable = [
        'order_number', 'payment_order_number', 'order_status', 
        'amount', 'address', 'user_id',
    ];


    protected static function boot(){
        parent::boot();

        static::creating(function ($query) {
            $query->order_number = $query->order_number ?? 'LPB' . now()->timestamp;
            $query->order_status = $query->order_status ?? Order::orderStatusesIndex(Order::NOT_SELECTED_PAYMENT);
            $query->payment_order_number = $query->payment_order_number ?? '';
        });
    }

    public static function orderStatusesIndex($targetName){
        foreach(self::orderStatuses as $index => $name){
            if ($targetName == $name) {
                return $index;
            }
        }
        return null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderStatusStr()
    {
        return self::orderStatuses[$this->order_status];
    }

    public function getRouteKeyName()
    {
        return 'order_number';
    }

    public function setToPaid()
    {
        $this->order_status = Order::orderStatusesIndex(self::PAID);
        $this->save();
    }
}
