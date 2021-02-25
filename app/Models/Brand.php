<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'search_key', 'order_index', 'show_in_list'];

    public static function getShowInListData(){
        return self::where('show_in_list', true)->get();
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
