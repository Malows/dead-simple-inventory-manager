<?php

use App\Models\User;
use App\Policies\UserPolicy;

beforeEach(function () {
    $this->policy = new UserPolicy();
    $this->admin = new User(['role' => 'admin']);
    $this->user = new User(['role' => 'user']);
    $this->anotherUser = new User(['role' => 'user']);
});

// viewAny tests
test('admin can view any users', function () {
    expect($this->policy->viewAny($this->admin))->toBeTrue();
});

test('non-admin cannot view any users', function () {
    expect($this->policy->viewAny($this->user))->toBeFalse();
});

// view tests
test('admin can view user', function () {
    expect($this->policy->view($this->admin, $this->user))->toBeTrue();
});

test('non-admin cannot view user', function () {
    expect($this->policy->view($this->user, $this->anotherUser))->toBeFalse();
});

// create tests
test('admin can create user', function () {
    expect($this->policy->create($this->admin))->toBeTrue();
});

test('non-admin cannot create user', function () {
    expect($this->policy->create($this->user))->toBeFalse();
});

// update tests
test('admin can update user', function () {
    expect($this->policy->update($this->admin, $this->user))->toBeTrue();
});

test('non-admin cannot update user', function () {
    expect($this->policy->update($this->user, $this->anotherUser))->toBeFalse();
});

// delete tests
test('admin can delete user', function () {
    expect($this->policy->delete($this->admin, $this->user))->toBeTrue();
});

test('non-admin cannot delete user', function () {
    expect($this->policy->delete($this->user, $this->anotherUser))->toBeFalse();
});

// restore tests
test('admin can restore user', function () {
    expect($this->policy->restore($this->admin, $this->user))->toBeTrue();
});

test('non-admin cannot restore user', function () {
    expect($this->policy->restore($this->user, $this->anotherUser))->toBeFalse();
});

// forceDelete tests
test('admin can force delete user', function () {
    expect($this->policy->forceDelete($this->admin, $this->user))->toBeTrue();
});

test('non-admin cannot force delete user', function () {
    expect($this->policy->forceDelete($this->user, $this->anotherUser))->toBeFalse();
});
