<?php

namespace Database\Seeders;

use App\Models\Student;
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
        // Student::factory(100)->create();

        $this->call([
            StudentSeeder::class,
        ]);
        // User::factory()->create([
        //     'name' => 'jon',
        //     'email' => 'jon@example.com',
        // ]);
    }
}
