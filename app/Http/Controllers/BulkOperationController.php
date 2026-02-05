<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;
use App\Services\PriceOperation;
use App\Services\StockOperation;
use Illuminate\Http\Request;

class BulkOperationController extends Controller
{
    public function __construct(
        protected PriceOperation $price,
        protected StockOperation $stock,
    ) {}

    public function byBrand(Request $request, Brand $brand)
    {
        $productIds = $brand->products()->pluck('id')->toArray();

        $user = $request->user('api');

        $values = $request->validated();

        $this->transform($user, $productIds, $values['type'], $values['value']);
    }

    public function byCategory(Request $request, Category $category)
    {
        $productIds = $category->products()->pluck('id')->toArray();

        $user = $request->user('api');

        $values = $request->validated();

        $this->transform($user, $productIds, $values['type'], $values['value']);
    }

    public function bySupplier(Request $request, Supplier $supplier)
    {
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

    public function updateStock(Request $request)
    {
        $values = $request->validated();

        $user = $request->user('api');

        return $this->stock->updateStock(
            $user,
            $values['changes'],
            'bulk_adjustment'
        );
    }
}
