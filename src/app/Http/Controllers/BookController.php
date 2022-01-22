<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index()
    {
        // $books = Auth::user()->account->books()->paginate(10);
        $books = Book::where('account_id', Auth::user()->account_id)
            ->paginate(10);

        return view('books.index', compact('books'));
    }

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

        return redirect()->route('books.index');
    }

    public function create()
    {
        return view('books.create');
    }
}
