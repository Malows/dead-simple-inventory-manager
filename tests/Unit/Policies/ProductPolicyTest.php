<?php

use App\Models\Product;
use App\Models\User;
use App\Policies\ProductPolicy;

beforeEach(function () {
    $this->policy = new ProductPolicy;
    $this->admin = new User(['id' => 99, 'role' => 'admin']);
    $this->user = new User(['id' => 1, 'role' => 'user']);
    $this->anotherUser = new User(['id' => 2, 'role' => 'user']);

    $this->user->id = 1;
    $this->anotherUser->id = 2;

    $this->ownProduct = new Product(['user_id' => 1]);
    $this->ownProduct->user_id = 1;

    $this->otherProduct = new Product(['user_id' => 2]);
    $this->otherProduct->user_id = 2;
});

// viewAny tests
test('any authenticated user can view any products', function () {
    expect($this->policy->viewAny($this->user))->toBeTrue();
    expect($this->policy->viewAny($this->admin))->toBeTrue();
});

// view tests
test('admin can view any product', function () {
    expect($this->policy->view($this->admin, $this->ownProduct))->toBeTrue();
    expect($this->policy->view($this->admin, $this->otherProduct))->toBeTrue();
});

test('user can view own product', function () {
    expect($this->policy->view($this->user, $this->ownProduct))->toBeTrue();
});

test('user cannot view other users product', function () {
    expect($this->policy->view($this->user, $this->otherProduct))->toBeFalse();
});

// create tests
test('any authenticated user can create product', function () {
    expect($this->policy->create($this->user))->toBeTrue();
    expect($this->policy->create($this->admin))->toBeTrue();
});

// update tests
test('admin can update any product', function () {
    expect($this->policy->update($this->admin, $this->ownProduct))->toBeTrue();
    expect($this->policy->update($this->admin, $this->otherProduct))->toBeTrue();
});

test('user can update own product', function () {
    expect($this->policy->update($this->user, $this->ownProduct))->toBeTrue();
});

test('user cannot update other users product', function () {
    expect($this->policy->update($this->user, $this->otherProduct))->toBeFalse();
});

// delete tests
test('admin can delete any product', function () {
    expect($this->policy->delete($this->admin, $this->ownProduct))->toBeTrue();
    expect($this->policy->delete($this->admin, $this->otherProduct))->toBeTrue();
});

test('user can delete own product', function () {
    expect($this->policy->delete($this->user, $this->ownProduct))->toBeTrue();
});

test('user cannot delete other users product', function () {
    expect($this->policy->delete($this->user, $this->otherProduct))->toBeFalse();
});

// restore tests
test('admin can restore product', function () {
    expect($this->policy->restore($this->admin, $this->ownProduct))->toBeTrue();
});

test('user cannot restore product', function () {
    expect($this->policy->restore($this->user, $this->ownProduct))->toBeFalse();
});

// forceDelete tests
test('admin can force delete product', function () {
    expect($this->policy->forceDelete($this->admin, $this->ownProduct))->toBeTrue();
});

test('user cannot force delete product', function () {
    expect($this->policy->forceDelete($this->user, $this->ownProduct))->toBeFalse();
});

// updateStock tests
test('admin can update stock of any product', function () {
    expect($this->policy->updateStock($this->admin, $this->ownProduct))->toBeTrue();
    expect($this->policy->updateStock($this->admin, $this->otherProduct))->toBeTrue();
});

test('user can update stock of own product', function () {
    expect($this->policy->updateStock($this->user, $this->ownProduct))->toBeTrue();
});

test('user cannot update stock of other users product', function () {
    expect($this->policy->updateStock($this->user, $this->otherProduct))->toBeFalse();
});
