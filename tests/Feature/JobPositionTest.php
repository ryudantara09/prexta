<?php

use App\Models\JobPosition;
use App\Models\User;

test('guests cannot access job positions', function () {
    $response = $this->get('/dashboard/job-positions');

    $response->assertRedirect('/login');
});

test('authenticated users can access job positions index', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard/job-positions');

    $response->assertStatus(200);
});

test('can create a job position', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/dashboard/job-positions', [
        'title' => 'Software Engineer',
        'description' => 'Great job',
        'city' => 'New York',
        'country' => 'USA',
    ]);

    $response->assertRedirect('/dashboard/job-positions');
    $this->assertDatabaseHas('job_positions', ['title' => 'Software Engineer']);
});

test('can update a job position', function () {
    $user = User::factory()->create();
    $job = JobPosition::create([
        'title' => 'Old Title',
        'description' => 'Old Desc',
        'city' => 'Old City',
        'country' => 'Old Country',
    ]);

    $response = $this->actingAs($user)->put("/dashboard/job-positions/{$job->id}", [
        'title' => 'New Title',
        'description' => 'New Desc',
        'city' => 'New City',
        'country' => 'New Country',
    ]);

    $response->assertRedirect('/dashboard/job-positions');
    $this->assertDatabaseHas('job_positions', ['title' => 'New Title']);
});

test('can delete a job position', function () {
    $user = User::factory()->create();
    $job = JobPosition::create([
        'title' => 'To Delete',
        'description' => 'Desc',
        'city' => 'City',
        'country' => 'Country',
    ]);

    $response = $this->actingAs($user)->delete("/dashboard/job-positions/{$job->id}");

    $response->assertRedirect('/dashboard/job-positions');
    $this->assertDatabaseMissing('job_positions', ['id' => $job->id]);
});
