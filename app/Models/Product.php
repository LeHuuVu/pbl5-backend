<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function Company(){
        return $this->belongsTo(Company::class);
    }

    public function Order(){
        return $this->belongsToMany(Order::class)->withPivot('total', 'amount');
    }

    public function Review(){
        return $this->hasMany(Review::class);
    }

    public function Category(){
        return $this->belongsToMany(Category::class);
    }
}
