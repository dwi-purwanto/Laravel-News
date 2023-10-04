<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','description', 'slug',
    ];

    public function image()
    {
        return $this->hasOne(NewsImage::class);
    }

    public function comments()
    {
        return $this->hasMany(NewsComment::class);
    }
}
