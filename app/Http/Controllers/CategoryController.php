<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Book;

class CategoryController extends Controller
{
    public function All()
    {
        return Category::all();
    }
    public function Books($category)
    {
        if ($category != 'all') {
            $cat = Category::where('name', $category)->firstOrFail();
            return $cat->booksAvailable;
        }
        else
        {
            return Book::where('available', '0')->get();
        }
    }
}
