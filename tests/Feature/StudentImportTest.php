<?php
use App\Services\Students\StudentService;
use Mockery\MockInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(fn() => Storage::fake());

describe('import students data', function () {

    it('import students data successfully', function () {
        $file = UploadedFile::fake()->create('students.xlsx');
        $this->mock(StudentService::class)
             ->shouldReceive()
             ->import()
             ->once()
             ->with($file)
             ->andReturn(['message' => 'Student data import is in progress...']);

        $this->postJson(route('students.import'), ['file' => $file])
             ->assertStatus(200)
             ->assertJson(['message' => 'Student data import is in progress...']);
    });

    it('returns error when file is not provided', function () {
        $this->mock(StudentService::class)
             ->shouldNotReceive()
             ->import();

        $this->postJson(route('students.import'), ['file' => null])
             ->assertStatus(422)
             ->assertInvalid([
                'file' => 'The file field is required.',
            ]);
    });

});
