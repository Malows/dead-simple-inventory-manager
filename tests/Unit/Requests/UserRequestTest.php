<?php

use App\Http\Requests\User\UpdatePasswordRequest;

test('update password request rules keys', function () {
    $request = new UpdatePasswordRequest;

    $rules = $request->rules();

    expect($rules)->toHaveKeys(['password']);
});

test('update password request rules values', function () {
    $request = new UpdatePasswordRequest;

    $rules = $request->rules();

    expect($rules['password'])->toBeArray();
    expect($rules['password'])->toContain('required');
    expect($rules['password'])->toContain('string');
    expect($rules['password'])->toContain('min:8');
    expect($rules['password'])->toContain('confirmed');
});
