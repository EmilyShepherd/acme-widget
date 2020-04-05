<?php

namespace AcmeWidget\Test\Entities;

use AcmeWidget\Entities\ArrayCatalog;
use AcmeWidget\Entities\StandardProduct;
use PHPUnit\Framework\TestCase;

/**
 * Simple set of unit tests to ensure the ArrayCatalog works as expected
 */
class ArrayCatalogTest extends TestCase
{
    /**
     * An instance of the ArrayCatalog to test
     *
     * @var ArrayCatalog
     */
    private $catalog;

    /**
     * An instance of a StandardProduct that exists inside the catalog
     *
     * @var StandardProduct
     */
    private $product;

    /**
     * Sets up each test by creating an ArrayCatalog with the Red Widget
     */
    public function setUp(): void
    {
        $this->product = new StandardProduct('Red Widget', 'R01', 3295);
        $this->catalog = new ArrayCatalog([$this->product]);
    }

    /**
     * Tests that looking up a product works
     */
    public function testGetProduct(): void
    {
        $product = $this->catalog->getProduct('R01');

        $this->assertNotNull($product);
        $this->assertSame($this->product, $product);
    }

    /**
     * Tests that looking up a product that doesn't exist returns null
     */
    public function testMissingProduct(): void
    {
        $this->assertNull($this->catalog->getProduct('G01'));
    }
}