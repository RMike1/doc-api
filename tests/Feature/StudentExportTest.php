<?php
use App\Services\Students\StudentService;
use Mockery\MockInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(fn() => Storage::fake());

describe('export students data', function(){
     
     it('support export students data', function (string $format) {
         $this->mock(StudentService::class)
              ->shouldReceive()
              ->export()
              ->with($format)
              ->once()
              ->andReturn(['message' => 'Export started...']);
     
         $this->getJson(route('students.export', ['file_type' => $format]))
              ->assertStatus(200)
              ->assertExactJson(['message' => 'Export started...']);
     })->with(['excel','pdf']);

     
     it('returns error when unsupported format is selected', function () {
         $this->mock(StudentService::class)
              ->shouldNotReceive()
              ->export();
     
         $this->getJson(route('students.export', ['file_type' => 'docx']))
              ->assertStatus(422)
              ->assertInvalid(['file_type']);
     });
});
