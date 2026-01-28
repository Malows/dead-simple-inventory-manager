<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\SupplierRequest as Request;
use PHPUnit\Framework\TestCase;

class SupplierRequestTest extends TestCase
{
    protected $validator = Request::class;

    /**
     * Check if the FormRequest is authorized
     *
     * @return void
     */
    public function test_authorized()
    {
        $this->assertTrue((new $this->validator)->authorize());
    }

    /**
     * Check the rules keys of the FormRequest.
     *
     * @return void
     */
    public function test_rules_keys()
    {
        $keys = (new $this->validator)->rules();
        $keys = array_keys($keys);
        sort($keys);

        $this->assertCount(1, $keys);
        $this->assertEquals(['name'], $keys);
    }

    /**
     * Check the rules of the FormRequest.
     *
     * @return void
     */
    public function test_rules_values()
    {
        $rules = (new $this->validator)->rules();

        $this->assertIsArray($rules);

        $this->assertEquals(['required'], $rules['name']);
    }
}
