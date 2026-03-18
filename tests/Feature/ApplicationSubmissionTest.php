<?php

use App\Models\JobPosition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('allows submitting an application without a cover letter', function () {
    Storage::fake('public');

    $job = JobPosition::create([
        'title' => 'Backend Engineer',
        'description' => 'Build APIs',
        'city' => 'Remote',
        'country' => 'Global',
    ]);

    $response = $this->post(route('applications.store', $job), [
        'full_name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'cv' => UploadedFile::fake()->create('resume.pdf', 120, 'application/pdf'),
    ]);

    $response->assertRedirect(route('applications.success'));

    $this->assertDatabaseHas('applicants', [
        'email' => 'jane@example.com',
        'full_name' => 'Jane Doe',
    ]);

    $this->assertDatabaseHas('applications', [
        'job_position_id' => $job->id,
        'cover_letter' => null,
    ]);
});
