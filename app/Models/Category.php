<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'name',
        'slug',
    ];

    // Opsional: Jika ingin otomatis membuat slug dari nama
    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function($category) {
    //         $category->slug = \Str::slug($category->name);
    //     });

    //     static::updating(function($category) {
    //         $category->slug = \Str::slug($category->name);
    //     });
    // }
}