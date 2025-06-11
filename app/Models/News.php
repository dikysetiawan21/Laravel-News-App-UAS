<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Untuk slug

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'image',
        'content',
        'status',
        'published_at',
    ];

    // Relasi ke User (pengirim berita)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Mutator untuk otomatis membuat slug saat menyimpan/memperbarui
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            $news->slug = Str::slug($news->title);
        });

        static::updating(function ($news) {
            $news->slug = Str::slug($news->title);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}