<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'book_id', 'utc_start', 'utc_end'];
    
    public function book()
    {
        return $this->belongsTo(\App\Models\Book::class);
    }
}
