<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\Application;
use App\Models\JobPosition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_job_position()
    {
        $job = JobPosition::create([
            'title' => 'Software Engineer',
            'description' => 'Develop cool stuff',
            'city' => 'Remote',
            'country' => 'Global',
        ]);

        $this->assertDatabaseHas('job_positions', ['title' => 'Software Engineer']);
    }

    public function test_can_create_applicant_and_application()
    {
        $job = JobPosition::create([
            'title' => 'Software Engineer',
            'description' => 'Develop cool stuff',
            'city' => 'Remote',
            'country' => 'Global',
        ]);

        $applicant = Applicant::create([
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $application = Application::create([
            'job_position_id' => $job->id,
            'applicant_id' => $applicant->id,
            'cv_path' => 'path/to/cv.pdf',
            'cover_letter' => 'I am the best!',
        ]);

        $this->assertDatabaseHas('applicants', ['email' => 'john@example.com']);
        $this->assertDatabaseHas('applications', ['cv_path' => 'path/to/cv.pdf']);

        // Test relationships
        $this->assertTrue($job->applications->contains($application));
        $this->assertTrue($applicant->applications->contains($application));
    }
}
