<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name','user_id','product_description','product_price'
    ];

    protected $table = 'products';

    public function user(){
        return $this->belongsTo('App\User');
    }
}
