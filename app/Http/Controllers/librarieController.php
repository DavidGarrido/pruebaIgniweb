<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;

class librarieController extends Controller
{
    public function list ()
    {
        return Inertia::render('book/list', [
            'count_reserves' => count(auth()->user()->reservas),
            'all_cat' => Category::all(),
        ]);
    }
}
