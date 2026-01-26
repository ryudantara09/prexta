<?php

it('accepts contact form submissions', function () {
    $response = $this->post('/contact', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'This is a test message with more than 10 characters.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status', 'Message envoyé avec succès !');
});

it('validates contact form submissions', function () {
    $response = $this->post('/contact', [
        'name' => '',
        'email' => 'invalid-email',
        'message' => 'short',
    ]);

    $response->assertSessionHasErrors(['name', 'email', 'message']);
});
