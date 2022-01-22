<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function store(Request $request)
    {
        $attributes = $request->all();

        $attributes['account_id'] = Auth::user()->account_id;

        Book::create([
            'title' => $attributes['title'],
            'author' => $attributes['author'],
            'release_date' => $attributes['release_date'],
            'account_id' => $attributes['account_id'],
        ]);
    }

    public function create()
    {
        return view('books.create');
    }
}
