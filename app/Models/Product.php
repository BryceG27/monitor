<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $fillable = [
        'name',
        'price'
    ];

    public function orders() {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public static function validate($request) {
        return $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0'
        ]);
    }
}
