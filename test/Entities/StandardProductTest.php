<?php

namespace AcmeWidget\Test\Entities;

use AcmeWidget\Entities\StandardProduct;
use PHPUnit\Framework\TestCase;

/**
 * Simple set of unit tests to ensure the getters and constructor of the
 * StandardProduct entity is working as expected
 */
class StandardProductTest extends TestCase
{
    /**
     * An instance of a StandardProduct to test
     *
     * @var StandardProduct
     */
    private $product;

    /**
     * Sets up each test by creating a StandardProduct with the Red
     * Widget's details
     */
    public function setUp(): void
    {
        $this->product = new StandardProduct(
            'Red Widget',
            'R01',
            3295
        );
    }

    /**
     * Tests that the name sets expected
     */
    public function testNameGetter(): void
    {
        $this->assertSame('Red Widget', $this->product->getName());
    }

    /**
     * Tests that the product code sets expected
     */
    public function testProductCodeGetter(): void
    {
        $this->assertSame('R01', $this->product->getProductCode());
    }

    /**
     * Tests that the base price sets expected
     */
    public function testBasePriceGetter(): void
    {
        $this->assertSame(3295, $this->product->getBasePrice());
    }
}