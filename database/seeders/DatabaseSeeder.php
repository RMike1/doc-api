<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ExportRecord;
use Illuminate\Database\Seeder;
use Database\Seeders\TeacherSeeder;

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
