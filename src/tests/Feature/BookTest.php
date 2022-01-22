<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function signIn($user = null)
    {
        $user = $user ?? User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    protected function bookPayload()
    {
        $bookPayload = new BookPayload();

        return $bookPayload->create();
    }

    public function test_when_guest_creates_a_book_then_302_is_returend()
    {
        $this->get('books/create')->assertStatus(302);
        $this->post('books')->assertStatus(302);
    }

    public function test_when_user_creates_a_book_then_is_saved_in_database()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        $this->get('books/create')->assertOk();

        $attributes = $this->bookPayload();
        $this->post('books', $attributes);

        $attributes['account_id'] = $user->account->id;
        $this->assertDatabaseHas('books', $attributes);
    }

    public function test_after_user_creates_a_book_then_is_redirected_to_index()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $this->post('books', $this->bookPayload())->assertRedirect(route('books.index'));
    }

    public function test_when_user_views_books_then_his_account_books_are_shown()
    {
        $this->withoutExceptionHandling();

        $signedUser = $this->signIn();
        $books = Book::factory()->for($signedUser->account)->count(5)->create();

        $response = $this->get('books');

        foreach ($books as $book) {
            $response->assertSee([$book->title, $book->author, $book->release_date]);
        }
    }

    public function test_when_user_views_books_then_account_books_from_other_users_are_shown()
    {
        $this->withoutExceptionHandling();

        $signedUser = $this->signIn();
        $user = User::factory()->for($signedUser->account)->create();
        $books = Book::factory()->for($user->account)->count(5)->create();

        $response = $this->get('books');

        foreach ($books as $book) {
            $response->assertSee([$book->title, $book->author, $book->release_date]);
        }
    }
}
