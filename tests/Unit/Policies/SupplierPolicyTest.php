<?php

use App\Models\Supplier;
use App\Models\User;
use App\Policies\SupplierPolicy;

beforeEach(function () {
    $this->policy = new SupplierPolicy;
    $this->admin = new User(['id' => 99, 'role' => 'admin']);
    $this->user = new User(['id' => 1, 'role' => 'user']);
    $this->anotherUser = new User(['id' => 2, 'role' => 'user']);

    $this->user->id = 1;
    $this->anotherUser->id = 2;

    $this->ownSupplier = new Supplier(['user_id' => 1]);
    $this->ownSupplier->user_id = 1;

    $this->otherSupplier = new Supplier(['user_id' => 2]);
    $this->otherSupplier->user_id = 2;
});

// viewAny tests
test('any authenticated user can view any suppliers', function () {
    expect($this->policy->viewAny($this->user))->toBeTrue();
    expect($this->policy->viewAny($this->admin))->toBeTrue();
});

// view tests
test('admin can view any supplier', function () {
    expect($this->policy->view($this->admin, $this->ownSupplier))->toBeTrue();
    expect($this->policy->view($this->admin, $this->otherSupplier))->toBeTrue();
});

test('user can view own supplier', function () {
    expect($this->policy->view($this->user, $this->ownSupplier))->toBeTrue();
});

test('user cannot view other users supplier', function () {
    expect($this->policy->view($this->user, $this->otherSupplier))->toBeFalse();
});

// create tests
test('any authenticated user can create supplier', function () {
    expect($this->policy->create($this->user))->toBeTrue();
    expect($this->policy->create($this->admin))->toBeTrue();
});

// update tests
test('admin can update any supplier', function () {
    expect($this->policy->update($this->admin, $this->ownSupplier))->toBeTrue();
    expect($this->policy->update($this->admin, $this->otherSupplier))->toBeTrue();
});

test('user can update own supplier', function () {
    expect($this->policy->update($this->user, $this->ownSupplier))->toBeTrue();
});

test('user cannot update other users supplier', function () {
    expect($this->policy->update($this->user, $this->otherSupplier))->toBeFalse();
});

// delete tests
test('admin can delete any supplier', function () {
    expect($this->policy->delete($this->admin, $this->ownSupplier))->toBeTrue();
    expect($this->policy->delete($this->admin, $this->otherSupplier))->toBeTrue();
});

test('user can delete own supplier', function () {
    expect($this->policy->delete($this->user, $this->ownSupplier))->toBeTrue();
});

test('user cannot delete other users supplier', function () {
    expect($this->policy->delete($this->user, $this->otherSupplier))->toBeFalse();
});

// restore tests
test('admin can restore supplier', function () {
    expect($this->policy->restore($this->admin, $this->ownSupplier))->toBeTrue();
});

test('user cannot restore supplier', function () {
    expect($this->policy->restore($this->user, $this->ownSupplier))->toBeFalse();
});

// forceDelete tests
test('admin can force delete supplier', function () {
    expect($this->policy->forceDelete($this->admin, $this->ownSupplier))->toBeTrue();
});

test('user cannot force delete supplier', function () {
    expect($this->policy->forceDelete($this->user, $this->ownSupplier))->toBeFalse();
});
