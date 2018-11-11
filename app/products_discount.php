<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class products_discount extends Model
{
    protected $table = 'products_discount';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'amount','product_id'
    ];
}
