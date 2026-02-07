<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bulk\PriceRequest;
use App\Http\Requests\Bulk\StockRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use App\Services\PriceOperation;
use App\Services\StockOperation;

class BulkOperationController extends Controller
{
    public function __construct(
        protected PriceOperation $price,
        protected StockOperation $stock,
    ) {}

    public function byBrand(PriceRequest $request, Brand $brand)
    {
        $this->authorize('byBrand', $brand);

        $productIds = $brand->products()->pluck('id')->toArray();

        $user = $request->user('api');

        $values = $request->validated();

        $this->transform($user, $productIds, $values['type'], $values['value']);
    }

    public function byCategory(PriceRequest $request, Category $category)
    {
        $this->authorize('byCategory', $category);

        $productIds = $category->products()->pluck('id')->toArray();

        $user = $request->user('api');

        $values = $request->validated();

        $this->transform($user, $productIds, $values['type'], $values['value']);
    }

    public function bySupplier(PriceRequest $request, Supplier $supplier)
    {
        $this->authorize('bySupplier', $supplier);

        $productIds = $supplier->products()->pluck('id')->toArray();

        $user = $request->user('api');

        $values = $request->validated();

        $this->transform($user, $productIds, $values['type'], $values['value']);
    }

    protected function transform(User $user, array $productIds, string $type, float|int $value)
    {
        if ($type === 'price_percentage') {
            return $this->price->percentualPriceTransformation(
                $user,
                $productIds,
                $value
            );
        } elseif ($type === 'price_fixed') {
            return $this->price->fixedPriceTransformation(
                $user,
                $productIds,
                $value
            );
        }
    }

    public function updateStock(StockRequest $request)
    {
        $this->authorize('updateStock');

        $values = $request->validated();

        $user = $request->user('api');

        return $this->stock->updateStock(
            $user,
            $values['changes'],
            'bulk_adjustment'
        );
    }
}
