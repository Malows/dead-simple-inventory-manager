<?php

use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;

beforeEach(function () {
    $this->policy = new CategoryPolicy();
    $this->admin = new User(['id' => 99, 'role' => 'admin']);
    $this->user = new User(['id' => 1, 'role' => 'user']);
    $this->anotherUser = new User(['id' => 2, 'role' => 'user']);

    $this->user->id = 1;
    $this->anotherUser->id = 2;

    $this->ownCategory = new Category(['user_id' => 1]);
    $this->ownCategory->user_id = 1;

    $this->otherCategory = new Category(['user_id' => 2]);
    $this->otherCategory->user_id = 2;
});

// viewAny tests
test('any authenticated user can view any categories', function () {
    expect($this->policy->viewAny($this->user))->toBeTrue();
    expect($this->policy->viewAny($this->admin))->toBeTrue();
});

// view tests
test('admin can view any category', function () {
    expect($this->policy->view($this->admin, $this->ownCategory))->toBeTrue();
    expect($this->policy->view($this->admin, $this->otherCategory))->toBeTrue();
});

test('user can view own category', function () {
    expect($this->policy->view($this->user, $this->ownCategory))->toBeTrue();
});

test('user cannot view other users category', function () {
    expect($this->policy->view($this->user, $this->otherCategory))->toBeFalse();
});

// create tests
test('any authenticated user can create category', function () {
    expect($this->policy->create($this->user))->toBeTrue();
    expect($this->policy->create($this->admin))->toBeTrue();
});

// update tests
test('admin can update any category', function () {
    expect($this->policy->update($this->admin, $this->ownCategory))->toBeTrue();
    expect($this->policy->update($this->admin, $this->otherCategory))->toBeTrue();
});

test('user can update own category', function () {
    expect($this->policy->update($this->user, $this->ownCategory))->toBeTrue();
});

test('user cannot update other users category', function () {
    expect($this->policy->update($this->user, $this->otherCategory))->toBeFalse();
});

// delete tests
test('admin can delete any category', function () {
    expect($this->policy->delete($this->admin, $this->ownCategory))->toBeTrue();
    expect($this->policy->delete($this->admin, $this->otherCategory))->toBeTrue();
});

test('user can delete own category', function () {
    expect($this->policy->delete($this->user, $this->ownCategory))->toBeTrue();
});

test('user cannot delete other users category', function () {
    expect($this->policy->delete($this->user, $this->otherCategory))->toBeFalse();
});

// restore tests
test('admin can restore category', function () {
    expect($this->policy->restore($this->admin, $this->ownCategory))->toBeTrue();
});

test('user cannot restore category', function () {
    expect($this->policy->restore($this->user, $this->ownCategory))->toBeFalse();
});

// forceDelete tests
test('admin can force delete category', function () {
    expect($this->policy->forceDelete($this->admin, $this->ownCategory))->toBeTrue();
});

test('user cannot force delete category', function () {
    expect($this->policy->forceDelete($this->user, $this->ownCategory))->toBeFalse();
});
