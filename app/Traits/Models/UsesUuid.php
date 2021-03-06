<?php

namespace App\Traits\Models;

use Illuminate\Support\Str;

trait UsesUuid
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getRouteKeyName()} = Str::uuid();
        });
    }
}
