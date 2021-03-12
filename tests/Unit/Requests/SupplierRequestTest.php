<?php

namespace Tests\Unit\Requests;

use PHPUnit\Framework\TestCase;
use App\Http\Requests\SupplierRequest as Request;

class SupplierRequestTest extends TestCase
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

        $this->assertCount(1, $keys);
        $this->assertEquals(['name'], $keys);
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

        $this->assertEquals(['required'], $rules['name']);
    }
}
