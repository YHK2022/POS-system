<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [

        "name", 'image', "parent_id", "is_active", "company_id",
    ];

    public function product()
    {
        return $this->hasMany('App\Product');
    }
}