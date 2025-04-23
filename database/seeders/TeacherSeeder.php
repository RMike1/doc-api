<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TeacherSeeder extends Seeder
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
                'email' => fake()->email(),
                'subject' => fake()->word(),
            ];
            if (($i + 1) % $chunkSize === 0 || $i === $totalRecords - 1) {
                DB::table('teachers')->insert($data);
                $data = [];
            }
        }
        DB::commit();
    }
}
