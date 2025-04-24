<?php

namespace Database\Seeders;

use App\Models\ExportRecord;
use App\Models\Student;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
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
            TeacherSeeder::class,
        ]);
        ExportRecord::factory(2)->create();
        // User::factory()->create([
        //     'name' => 'jon',
        //     'email' => 'jon@example.com',
        // ]);
    }
}
