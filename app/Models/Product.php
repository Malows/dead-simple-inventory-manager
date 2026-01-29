<?php

namespace App\Models;

use App\Traits\Models\UsesUuid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory, UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'stock',
        'min_stock_warning',
        'price',
        'supplier_id',
        'storage_location_id',
        'user_id',
    ];

    /**
     * Get the categories for the product.
     *
     * @return BelongsToMany<Category, $this>
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get the supplier for the product.
     *
     * @return BelongsTo<Supplier, $this>
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the storage location for the product.
     *
     * @return BelongsTo<StorageLocation, $this>
     */
    public function storageLocation(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class);
    }

    /**
     * Get the user that owns the product.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the warning state for the product.
     */
    public function warning(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => ($attributes['stock'] ?? 0) <= ($attributes['min_stock_warning'] ?? 0),
        );
    }

    /**
     * Set the stock of the product.
     */
    public function stock(): Attribute
    {
        return Attribute::make(
            set: fn (int $value) => [
                'stock' => $value,
                'last_stock_update' => now(),
            ],
        );
    }

    /**
     * Set the price of the product.
     */
    public function price(): Attribute
    {
        return Attribute::make(
            set: function (float $value) {
                $attributes = $this->attributes ?? [];
                $currentPrice = array_key_exists('price', $attributes)
                    ? (float) $attributes['price']
                    : null;

                // Only update the last_price_update timestamp when the price value actually changes.
                if ($currentPrice !== null && $currentPrice === (float) $value) {
                    return [
                        'price' => $value,
                    ];
                }

                return [
                    'price' => $value,
                    'last_price_update' => now(),
                ];
            },
        );
    }
}
