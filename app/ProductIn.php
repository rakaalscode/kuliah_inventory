<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductIn extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
