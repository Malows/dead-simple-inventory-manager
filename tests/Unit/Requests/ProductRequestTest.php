<?php

namespace Tests\Unit\Requests;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\ProductRequest as Request;

class ProductRequestTest extends TestCase
{
    protected $validator = Request::class;

    /**
     * Check if the FormRequest is authorized
     *
     * @return void
     */
    public function testAuthorized()
    {
        $this->assertTrue((new $this->validator())->authorize());
    }

    /**
     * Check the rules keys of the FormRequest.
     *
     * @return void
     */
    public function testRulesKeys()
    {
        $keys = (new $this->validator())->rules();
        $keys = array_keys($keys);
        sort($keys);

        $this->assertCount(9, $keys);
        $this->assertEquals([
            'categories',
            'categories.*',
            'code',
            'description',
            'min_stock_warning',
            'name',
            'price',
            'stock',
            'supplier_id',
        ], $keys);
    }

    /**
     * Check the rules of the FormRequest.
     *
     * @return void
     */
    public function testRulesValues()
    {
        $rules = (new $this->validator())->rules();

        $this->assertIsArray($rules);

        $this->assertEquals(['array'], $rules['categories']);
        $this->assertEquals(['exists:categories,id'], $rules['categories.*']);
        $this->assertEquals(['nullable'], $rules['code']);
        $this->assertEquals(['nullable', 'string'], $rules['description']);
        $this->assertEquals(['nullable', 'integer'], $rules['min_stock_warning']);
        $this->assertEquals(['required'], $rules['name']);
        $this->assertEquals(['nullable', 'numeric'], $rules['price']);
        $this->assertEquals(['required', 'integer'], $rules['stock']);
        $this->assertEquals(['nullable', 'exists:suppliers,id'], $rules['supplier_id']);
    }
}
