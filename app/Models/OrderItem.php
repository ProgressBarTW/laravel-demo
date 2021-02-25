<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'quantity', 'product_option_id'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function productOption()
    {
        return $this->belongsTo(ProductOption::class);
    }
}
