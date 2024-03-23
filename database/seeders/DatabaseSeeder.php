<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Leons',
            'email' => 's@s.com',
            'password' => 'labdien',
            'is_admin' => true
        ]);

        User::factory()->create([
            'name' => 'Test account',
            'email' => 'test@s.com',
            'password' => 'labdien'
        ]);

        \App\Models\Account::factory(2)
            ->has(\App\Models\Transactions::factory(100))
            ->create();
        
    }
}
