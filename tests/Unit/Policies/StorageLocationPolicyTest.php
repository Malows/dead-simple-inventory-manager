<?php

use App\Models\StorageLocation;
use App\Models\User;
use App\Policies\StorageLocationPolicy;

beforeEach(function () {
    $this->policy = new StorageLocationPolicy();
    $this->admin = new User(['id' => 99, 'role' => 'admin']);
    $this->user = new User(['id' => 1, 'role' => 'user']);
    $this->anotherUser = new User(['id' => 2, 'role' => 'user']);

    // Set the id property directly to ensure comparison works
    $this->user->id = 1;
    $this->anotherUser->id = 2;

    $this->ownStorageLocation = new StorageLocation(['user_id' => 1]);
    $this->ownStorageLocation->user_id = 1;

    $this->otherStorageLocation = new StorageLocation(['user_id' => 2]);
    $this->otherStorageLocation->user_id = 2;
});

// viewAny tests
test('any authenticated user can view any storage locations', function () {
    expect($this->policy->viewAny($this->user))->toBeTrue();
    expect($this->policy->viewAny($this->admin))->toBeTrue();
});

// view tests
test('admin can view any storage location', function () {
    expect($this->policy->view($this->admin, $this->ownStorageLocation))->toBeTrue();
    expect($this->policy->view($this->admin, $this->otherStorageLocation))->toBeTrue();
});

test('user can view own storage location', function () {
    expect($this->policy->view($this->user, $this->ownStorageLocation))->toBeTrue();
});

test('user cannot view other users storage location', function () {
    expect($this->policy->view($this->user, $this->otherStorageLocation))->toBeFalse();
});

// create tests
test('any authenticated user can create storage location', function () {
    expect($this->policy->create($this->user))->toBeTrue();
    expect($this->policy->create($this->admin))->toBeTrue();
});

// update tests
test('admin can update any storage location', function () {
    expect($this->policy->update($this->admin, $this->ownStorageLocation))->toBeTrue();
    expect($this->policy->update($this->admin, $this->otherStorageLocation))->toBeTrue();
});

test('user can update own storage location', function () {
    expect($this->policy->update($this->user, $this->ownStorageLocation))->toBeTrue();
});

test('user cannot update other users storage location', function () {
    expect($this->policy->update($this->user, $this->otherStorageLocation))->toBeFalse();
});

// delete tests
test('admin can delete any storage location', function () {
    expect($this->policy->delete($this->admin, $this->ownStorageLocation))->toBeTrue();
    expect($this->policy->delete($this->admin, $this->otherStorageLocation))->toBeTrue();
});

test('user can delete own storage location', function () {
    expect($this->policy->delete($this->user, $this->ownStorageLocation))->toBeTrue();
});

test('user cannot delete other users storage location', function () {
    expect($this->policy->delete($this->user, $this->otherStorageLocation))->toBeFalse();
});

// restore tests
test('admin can restore storage location', function () {
    expect($this->policy->restore($this->admin, $this->ownStorageLocation))->toBeTrue();
});

test('user cannot restore storage location', function () {
    expect($this->policy->restore($this->user, $this->ownStorageLocation))->toBeFalse();
});

// forceDelete tests
test('admin can force delete storage location', function () {
    expect($this->policy->forceDelete($this->admin, $this->ownStorageLocation))->toBeTrue();
});

test('user cannot force delete storage location', function () {
    expect($this->policy->forceDelete($this->user, $this->ownStorageLocation))->toBeFalse();
});
