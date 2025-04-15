<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::disableQueryLog();
        Schema::disableForeignKeyConstraints();
        DB::beginTransaction();
        $data = [];
        $chunkSize = 500;
        $totalRecords = 1000;

        for ($i = 0; $i < $totalRecords; $i++) {
            $data[] = [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'age' => fake()->numberBetween(15, 60),
                'student_no' => fake()->numberBetween(1000, 9999),
                'level' => fake()->randomElement(['one', 'two', 'three']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            if (($i + 1) % $chunkSize === 0 || $i === $totalRecords - 1) {
                DB::table('students')->insert($data);
                $data = [];
            }
        }
        DB::commit();
    }
}
