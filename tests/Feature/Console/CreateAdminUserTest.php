<?php

use App\Models\User;

test('command creates admin user successfully', function () {
    $this->artisan('user:create-admin')
        ->expectsQuestion('Administrator name', 'Admin Test')
        ->expectsQuestion('Administrator email', 'admin@test.com')
        ->expectsQuestion('Password (minimum 8 characters)', 'password123')
        ->expectsQuestion('Confirm password', 'password123')
        ->expectsOutput('✓ Admin user created successfully!')
        ->assertExitCode(0);

    $this->assertDatabaseHas('users', [
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'role' => 'admin',
    ]);

    $user = User::where('email', 'admin@test.com')->first();
    expect(\Illuminate\Support\Facades\Hash::check('password123', $user->password))->toBeTrue();
});

test('command fails when passwords do not match', function () {
    $this->artisan('user:create-admin')
        ->expectsQuestion('Administrator name', 'Admin Test')
        ->expectsQuestion('Administrator email', 'admin@test.com')
        ->expectsQuestion('Password (minimum 8 characters)', 'password123')
        ->expectsQuestion('Confirm password', 'differentpassword')
        ->expectsOutput('Passwords do not match.')
        ->assertExitCode(1);

    $this->assertDatabaseMissing('users', [
        'email' => 'admin@test.com',
    ]);
});

test('command fails when email already exists', function () {
    User::factory()->create(['email' => 'existing@test.com']);

    $this->artisan('user:create-admin')
        ->expectsQuestion('Administrator name', 'Admin Test')
        ->expectsQuestion('Administrator email', 'existing@test.com')
        ->expectsQuestion('Password (minimum 8 characters)', 'password123')
        ->expectsQuestion('Confirm password', 'password123')
        ->expectsOutput('Validation error:')
        ->assertExitCode(1);
});

test('command cancels when admin exists and user declines', function () {
    User::factory()->create(['role' => 'admin']);

    $this->artisan('user:create-admin')
        ->expectsConfirmation('Do you want to create another admin user anyway?', 'no')
        ->expectsOutput('Operation cancelled.')
        ->assertExitCode(0);
});

test('command continues when admin exists and user confirms', function () {
    User::factory()->create(['role' => 'admin', 'email' => 'existing-admin@test.com']);

    $this->artisan('user:create-admin')
        ->expectsConfirmation('Do you want to create another admin user anyway?', 'yes')
        ->expectsQuestion('Administrator name', 'Second Admin')
        ->expectsQuestion('Administrator email', 'admin2@test.com')
        ->expectsQuestion('Password (minimum 8 characters)', 'password123')
        ->expectsQuestion('Confirm password', 'password123')
        ->expectsOutput('✓ Admin user created successfully!')
        ->assertExitCode(0);

    $this->assertDatabaseHas('users', [
        'email' => 'admin2@test.com',
        'role' => 'admin',
    ]);
});

test('command fails with invalid email format', function () {
    $this->artisan('user:create-admin')
        ->expectsQuestion('Administrator name', 'Admin Test')
        ->expectsQuestion('Administrator email', 'invalid-email')
        ->expectsQuestion('Password (minimum 8 characters)', 'password123')
        ->expectsQuestion('Confirm password', 'password123')
        ->expectsOutput('Validation error:')
        ->assertExitCode(1);
});

test('command fails with short password', function () {
    $this->artisan('user:create-admin')
        ->expectsQuestion('Administrator name', 'Admin Test')
        ->expectsQuestion('Administrator email', 'admin@test.com')
        ->expectsQuestion('Password (minimum 8 characters)', 'short')
        ->expectsQuestion('Confirm password', 'short')
        ->expectsOutput('Validation error:')
        ->assertExitCode(1);
});

test('command fails with empty name', function () {
    $this->artisan('user:create-admin')
        ->expectsQuestion('Administrator name', '')
        ->expectsQuestion('Administrator email', 'admin@test.com')
        ->expectsQuestion('Password (minimum 8 characters)', 'password123')
        ->expectsQuestion('Confirm password', 'password123')
        ->expectsOutput('Validation error:')
        ->assertExitCode(1);
});

test('command handles database error gracefully', function () {
    // Create an admin with the email we'll try to use
    User::factory()->create(['email' => 'admin@test.com']);

    $this->artisan('user:create-admin')
        ->expectsQuestion('Administrator name', 'Admin Test')
        ->expectsQuestion('Administrator email', 'admin@test.com')
        ->expectsQuestion('Password (minimum 8 characters)', 'password123')
        ->expectsQuestion('Confirm password', 'password123')
        ->expectsOutput('Validation error:')
        ->assertExitCode(1);
});
