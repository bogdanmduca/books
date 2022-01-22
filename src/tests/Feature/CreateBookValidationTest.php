<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateBookValidationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
        $bookPayload = new BookPayload();
        $this->attributes = $bookPayload->create();
    }


    public function test_when_user_creates_a_book_then_title_is_required()
    {
        $this->attributes['title'] = '';

        $this->post('books', $this->attributes)->assertSessionHasErrors('title');
    }

    public function test_when_user_creates_a_book_then_title_is_max_255_character()
    {
        $this->attributes['title'] = Str::random(256);

        $this->post('books', $this->attributes)->assertSessionHasErrors('title');
    }

    public function test_when_user_creates_a_book_then_title_is_unique()
    {
        $this->attributes['title'] = Book::factory()->create()->title;

        $this->post('books', $this->attributes)->assertSessionHasErrors('title');
    }

    public function test_when_user_creates_a_book_then_author_is_required()
    {
        $this->attributes['author'] = '';

        $this->post('books', $this->attributes)->assertSessionHasErrors('author');
    }

    public function test_when_user_creates_a_book_then_author_is_max_255_character()
    {
        $this->attributes['author'] = Str::random(256);

        $this->post('books', $this->attributes)->assertSessionHasErrors('author');
    }

    public function test_when_user_creates_a_book_then_author_is_unique()
    {
        $this->attributes['author'] = Book::factory()->create()->author;

        $this->post('books', $this->attributes)->assertSessionHasErrors('author');
    }

    public function test_when_user_creates_a_book_then_release_date_is_required()
    {
        $this->attributes['release_date'] = '';

        $this->post('books', $this->attributes)->assertSessionHasErrors('release_date');
    }

    public function test_when_user_creates_a_book_then_release_date_format_is_Y_m_d()
    {
        $this->attributes['release_date'] = $this->faker()->word();

        $this->post('books', $this->attributes)->assertSessionHasErrors('release_date');
    }
}
