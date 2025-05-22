<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [

    ];

    public static function validate(Request $request) {
        return $request->validate([
            
        ]);
    }
}
