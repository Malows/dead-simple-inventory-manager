<?php

use App\Models\User;

test('admin can update password policy', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create();

    expect($admin->can('updatePassword', $user))->toBeTrue();
});

test('non-admin cannot update password policy', function () {
    $user = User::factory()->create(['role' => 'user']);
    $anotherUser = User::factory()->create();

    expect($user->can('updatePassword', $anotherUser))->toBeFalse();
});
