<?php

namespace Tests\Feature;

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
        return [
            'title' => $this->faker()->sentence(),
            'author' => $this->faker()->sentence(),
            'release_date' => now()->format('Y-m-d'),
        ];
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
}
