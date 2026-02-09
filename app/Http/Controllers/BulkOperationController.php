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
        $this->authorize('updatePrice', $brand);

        $productIds = $brand->products()->pluck('products.id')->toArray();

        $user = $request->user('api');

        $values = $request->validated();

        $this->transform($user, $productIds, $values['type'], $values['value']);
    }

    public function byCategory(PriceRequest $request, Category $category)
    {
        $this->authorize('updatePrice', $category);

        $productIds = $category->products()->pluck('products.id')->toArray();

        $user = $request->user('api');

        $values = $request->validated();

        $this->transform($user, $productIds, $values['type'], $values['value']);
    }

    public function bySupplier(PriceRequest $request, Supplier $supplier)
    {
        $this->authorize('updatePrice', $supplier);

        $productIds = $supplier->products()->pluck('products.id')->toArray();

        $user = $request->user('api');

        $values = $request->validated();

        $this->transform($user, $productIds, $values['type'], $values['value']);
    }

    public function updateStock(StockRequest $request)
    {
        $user = $request->user('api');

        $values = $request->validated();

        return $this->stock->updateStock(
            $user,
            $values['changes'],
            $values['type']
        );
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
}
