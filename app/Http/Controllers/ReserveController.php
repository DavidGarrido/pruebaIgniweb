<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserve;
use App\Models\Book;

class ReserveController extends Controller
{
    public function reserve($book, $dias)
    {
        $book = Book::where('id', $book)->firstOrFail();
        if($book->available == 0)
        {
            try {
                $reserve = Reserve::create([
                    'user_id' => auth()->user()->id,
                    'book_id' => $book->id,
                    'utc_start' => time(),
                    'utc_end' => time() + (86400 * $dias),
                ]);
                $book->available = 1;
                $book->save();
                return true;
            } catch (Exception $e) {
                return false;
            }

        }
    }
    public function All()
    {
        return Reserve::where('user_id', auth()->user()->id)->with('book')->get();
    }
    public function Delete($res)
    {
        try {
            $reserve = Reserve::where('id', $res)->firstOrFail();
            $reserve->book->available = 0;
            $reserve->book->save();
            $reserve->delete();
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
}
