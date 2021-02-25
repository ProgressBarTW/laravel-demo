<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'enabled', 'image'];

    static public function findIfEnabled($id){
        $option = self::where('enabled', true)->where('id', $id)->first();
        if ($option){
            if (@$option->product->is_published()){
                return $option;
            }
        }
        return null;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function fullName()
    {
        return @$this->product->name . ' ' . $this->name;
    }
}
