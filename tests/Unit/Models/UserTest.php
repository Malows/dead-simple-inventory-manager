<?php

use App\Models\User;

test('user with admin role has is_admin true', function () {
    $admin = new User(['role' => 'admin']);

    expect($admin->is_admin)->toBeTrue();
    expect($admin->role)->toBe('admin');
});

test('user with user role has is_admin false', function () {
    $user = new User(['role' => 'user']);

    expect($user->is_admin)->toBeFalse();
    expect($user->role)->toBe('user');
});

test('user with custom role has is_admin false', function () {
    $user = new User(['role' => 'moderator']);

    expect($user->is_admin)->toBeFalse();
    expect($user->role)->toBe('moderator');
});

test('user with null role has is_admin false', function () {
    $user = new User(['role' => null]);

    expect($user->is_admin)->toBeFalse();
});

test('user with empty role has is_admin false', function () {
    $user = new User(['role' => '']);

    expect($user->is_admin)->toBeFalse();
});
