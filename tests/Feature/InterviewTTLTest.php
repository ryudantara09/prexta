<?php

use App\Models\Algorithm;
use App\Models\Application;
use App\Models\Interview;
use App\Models\JobPosition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

test('pending interviews list includes only non-expired links', function () {
    $user = User::factory()->create();
    
    // Create a fresh interview (valid)
    $validInterview = Interview::create([
        'token' => Str::random(10),
        'status' => 'pending',
        'created_at' => Carbon::now()->subHours(2),
        'guest_name' => 'Valid Guest',
    ]);

    // Create an expired interview (invalid)
    $expiredInterview = Interview::create([
        'token' => Str::random(10),
        'status' => 'pending',
        'created_at' => Carbon::now()->subHours(25),
        'guest_name' => 'Expired Guest',
    ]);

    $response = $this->actingAs($user)->get(route('dashboard.interviews.pending'));

    $response->assertStatus(200);
    
    $props = $response->props('pendingInterviews');
    
    expect($props)->toHaveCount(1);
    expect($props[0]['id'])->toBe($validInterview->id);
    expect($props[0]['url'])->toContain(route('interview.book', $validInterview->token));
    // Verify TTL string presence
    expect($props[0]['ttl'])->not->toBeNull();
});

test('interview link fails if expired', function () {
    $expiredInterview = Interview::create([
        'token' => Str::random(10),
        'status' => 'pending',
        'created_at' => Carbon::now()->subHours(25),
        'guest_name' => 'Expired Guest',
    ]);

    $response = $this->get(route('interview.book', $expiredInterview->token));

    $response->assertNotFound();
});

test('interview link works if valid', function () {
    $validInterview = Interview::create([
        'token' => Str::random(10),
        'status' => 'pending',
        'created_at' => Carbon::now()->subHours(23),
        'guest_name' => 'Valid Guest',
    ]);

    $response = $this->get(route('interview.book', $validInterview->token));

    $response->assertStatus(200);
});
