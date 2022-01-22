<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create(['email' => 'user@example.com']);

        Book::factory()->for($user->account)->count(5)->create();
    }
}
