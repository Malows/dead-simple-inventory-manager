<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StockOperation
{
    public function __construct(protected InventoryService $inventory) {}

    /**
     * Update stock for multiple products based on provided changes.
     *
     * @param  User  $user  The user performing the operation.
     * @param  array  $changes  An associative array where keys are product IDs and values are the new stock values.
     * @param  string|null  $type  The type of stock movement (default is 'adjustment').
     * @return void
     */
    public function updateStock(User $user, array $changes, ?string $type = 'adjustment')
    {
        $productIds = array_keys($changes);

        $products = Product::whereIn('id', $productIds)
            ->where('user_id', $user->id)
            ->get();

        if ($products->isEmpty()) {
            return;
        }

        return DB::transaction(function () use ($user, $products, $changes, $type) {
            foreach ($products as $product) {
                $newStock = $changes[$product->id];
                $previousStock = $product->stock;
                $quantity = $newStock - $previousStock;

                $this->inventory->leanAdjustStock(
                    $user,
                    $product,
                    $quantity,
                    $type,
                    'Inventory movement'
                );
            }
        });
    }
}
