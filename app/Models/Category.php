<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function booksAvailable()
    {
        return $this->belongsToMany(\App\Models\Book::class)->where('available', '0');
    }
    public function books()
    {
        return $this->belongsToMany(\App\Models\Book::class);
    }

}
