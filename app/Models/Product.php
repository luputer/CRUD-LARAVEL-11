<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // protected $guarded = [
    //   'id'
    // ];
    // protected $fillable = [
    //     'image',
    //     'title', 
    //     'description', 
    //     'price', 
    //     'stock'
    // ];
    protected $fillable = ['image', 'title', 'description', 'price', 'stock'];
}
