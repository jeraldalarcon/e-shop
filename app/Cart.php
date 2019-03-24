<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'product_id','product_name','user_id','product_price','quantity','product_image'
    ];

    protected $table = 'cart';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
