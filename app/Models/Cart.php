<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    const PURCHASE = 'purchase';
    const NEXT_TIME = 'next_time';

    const types = [
        0 => self::PURCHASE,
        1 => self::NEXT_TIME,
    ];

    protected $fillable = [
        'cart_type',
        'user_id'
    ];

    public static function typeIndex($targetName){
        foreach(self::types as $index => $name){
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

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
