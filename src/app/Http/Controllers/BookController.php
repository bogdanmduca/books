<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function store(StoreBookRequest $request)
    {
        $attributes = $request->validated();

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
