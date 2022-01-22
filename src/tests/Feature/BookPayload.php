<?php

namespace Tests\Feature;

use App\Models\Book;

class BookPayload
{
    public function create()
    {
        $raw = Book::factory()->raw();
        return [
            'title' => $raw['title'],
            'author' => $raw['author'],
            'release_date' => $raw['release_date'],
        ];
    }
}
