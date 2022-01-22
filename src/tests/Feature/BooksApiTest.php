<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function withBasicAuth(User $user, $password = 'password'): self
    {
        return $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode("{$user->email}:{$password}")
        ]);
    }

    public function test_api_when_user_views_books__then_book_resource_is_returned()
    {
        $this->withoutExceptionHandling();

        $signedUser = User::factory()->create();
        $books = Book::factory()->for($signedUser->account)->count(5)->create();

        $response = $this
            ->withBasicAuth($signedUser)
            ->getJson('api/books');

        $expected = [];
        foreach ($books as $book) {
            $expected['data'][] = [
                'title' => $book->title,
                'author' => $book->author,
                'release_date' => $book->release_date,
            ];
        }

        $response
            ->assertStatus(200)
            ->assertJsonFragment($expected);
    }

    public function test_api_when_user_views_books_then_account_books_from_others_users_are_returned()
    {
        $this->withoutExceptionHandling();

        $signedUser = User::factory()->create();
        $user = User::factory()->for($signedUser->account)->create();
        $books = Book::factory()->for($user->account)->count(5)->create();

        $response = $this->withBasicAuth($signedUser)
            ->getJson('api/books');

        $expected = [];
        foreach ($books as $book) {
            $expected['data'][] = [
                'title' => $book->title,
                'author' => $book->author,
                'release_date' => $book->release_date,
            ];
        }

        $response
            ->assertStatus(200)
            ->assertJsonFragment($expected);
    }

    public function test_api_when_user_views_books_then_account_books_from_other_accounts_are_not_returned()
    {
        $this->withoutExceptionHandling();

        $signedUser = User::factory()->create();
        $books = Book::factory()->count(5)->create();

        $response = $this->withBasicAuth($signedUser)
            ->getJson('api/books');

        $expected = [];
        foreach ($books as $book) {
            $expected['data'][] = [
                'title' => $book->title,
                'author' => $book->author,
                'release_date' => $book->release_date,
            ];
        }

        $response
            ->assertStatus(200)
            ->assertJsonMissing($expected);
    }

    public function test_api_when_user_views_his_book_then_json_is_returned()
    {
        $this->withoutExceptionHandling();

        $signedUser = User::factory()->create();
        $book = Book::factory()->for($signedUser->account)->create();

        $response = $this->withBasicAuth($signedUser)
            ->getJson("api/books/{$book->id}");

        $expected = [
            'data' => [
                'title' => $book->title,
                'author' => $book->author,
                'release_date' => $book->release_date,
            ]
        ];

        $response
            ->assertStatus(200)
            ->assertJsonFragment($expected);
    }

    public function test_api_when_user_views_a_book_account_from_api_then_json_is_returned()
    {
        $this->withoutExceptionHandling();

        $signedUser = User::factory()->create();
        $user = User::factory()->for($signedUser->account)->create();
        $book = Book::factory()->for($user->account)->create();

        $response = $this
            ->withBasicAuth($signedUser)
            ->getJson("api/books/{$book->id}");

        $expected = [
            'data' => [
                'title' => $book->title,
                'author' => $book->author,
                'release_date' => $book->release_date,
            ]
        ];

        $response
            ->assertStatus(200)
            ->assertJsonFragment($expected);
    }

    public function test_api_when_user_views_a_book_from_other_account_403_is_returned()
    {
        $signedUser = User::factory()->create();
        $book = Book::factory()->create();

        $this->withBasicAuth($signedUser)
            ->getJson("api/books/{$book->id}")
            ->assertStatus(403);
    }

    public function test_api_when_user_views_his_book_data_is_logged()
    {
        $this->markTestSkipped('To be implemented');

        $this->withoutExceptionHandling();

        $signedUser = User::factory()->create();
        $book = Book::factory()->for($signedUser->account)->create();

        $expected = [
            'account_id' => $signedUser->account_id,
            'status_code' => 200,
            'content' => [
                'data' => [
                    'title' => $book->title,
                    'author' => $book->author,
                    'release_date' => $book->release_date,
                ]
            ]
        ];

        Log::shouldReceive('info')
            ->once()
            ->with($expected);


        $this->withBasicAuth($signedUser)
            ->getJson("api/books/{$book->id}");
    }
}
