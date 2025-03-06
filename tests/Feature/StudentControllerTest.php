<?php
use App\Services\Students\StudentService;
use Mockery\MockInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(fn()=>Storage::fake());

describe('import students data', function(){
    it('import students data successfully', function () {
        $file = UploadedFile::fake()->create('students.xlsx');
        $this->mock(StudentService::class)
            ->shouldReceive()
            ->import()
            ->once()
            ->with($file);
    
        $this->postJson(route('students.import'), ['file' => $file])
            ->assertStatus(200)
            ->assertJson(['message' => 'Student data import in progress...']);
    });
    
    it('returns error when file is not provided', function () {
        $this->mock(StudentService::class)
            ->shouldNotReceive()->import();
        $this->postJson(route('students.import'), ['file' => null])
            ->assertStatus(422)
            ->assertInvalid([
                'file' => 'The file field is required.',
            ]);
    });
});


it('export students data successfully', function () {
    $this->mock(StudentService::class)
    ->shouldReceive()->export()->once();

    $this->getJson(route('students.export'))
        ->assertStatus(200)
        ->assertExactJson(['message' => 'Export started...']);
});
