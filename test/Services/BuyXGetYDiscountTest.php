<?php

namespace AcmeWidget\Test\Entities;

use AcmeWidget\Services\BuyXGetYDiscount;
use AcmeWidget\Entities\SimpleInvoice;
use AcmeWidget\Entities\StandardProduct;
use PHPUnit\Framework\TestCase;

/**
 * Set of tests to ensure the BuyXGetYDiscount PriceModifier works as
 * expected
 */
class BuyXGetYDiscountTest extends TestCase
{
    /**
     * The cost of the test Product
     *
     * @var int
     */
    const COST = 4000;

    /**
     * An instance of a StandardProduct to test with
     *
     * @var StandardProduct
     */
    private $product;

    /**
     * An instance of the BuyXGetYDiscount modifier to test
     *
     * @var BuyXGetYDiscount
     */
    private $modifier;

    /**
     * Sets up each test by creating a StandardProduct for use in
     * testing
     */
    public function setUp(): void
    {
        $this->product = new StandardProduct('Test', 'T01', self::COST);
        $this->modifier = new BuyXGetYDiscount($this->product, 1, 50);
    }

    /**
     * Tests that one instance of a product does not affect the price
     */
    public function testSingleProduct(): void
    {
        $invoice = (new SimpleInvoice())
            ->addProductLineItem($this->product);

        $invoice = $this->modifier->processRules($invoice);

        $this->assertSame(self::COST, $invoice->getTotal());
    }

    /**
     * Tests that when the matching number of products are found, the
     * next is discounted
     */
    public function testDiscountApplied(): void
    {
        $invoice = (new SimpleInvoice())
            ->addProductLineItem($this->product)
            ->addProductLineItem($this->product);

        $invoice = $this->modifier->processRules($invoice);
        $items = $invoice->getLineItems();

        $totalCost = self::COST + self::COST / 2;

        $this->assertSame($totalCost, $invoice->getTotal());
        $this->assertCount(3, $items);
        $this->assertSame(
            'MultiBuy Discount For Test',
            $items[2]['description']
        );
        $this->assertSame(-1 * self::COST / 2, $items[2]['cost']);
    }

    /**
     * Tests that when other products are present, these are ignored
     */
    public function testOtherProducts(): void
    {
        $product = new StandardProduct('Other', 'O01', 1000);
        $invoice = (new SimpleInvoice())
            ->addProductLineItem($product)
            ->addProductLineItem($product);

        $invoice = $this->modifier->processRules($invoice);
        $items = $invoice->getLineItems();

        $this->assertSame(2000, $invoice->getTotal());
        $this->assertCount(2, $items);
    }

    /**
     * Tests that when an extra product is found, it is ignored
     */
    public function testExtraProduct(): void
    {
        $invoice = (new SimpleInvoice())
            ->addProductLineItem($this->product)
            ->addProductLineItem($this->product)
            ->addProductLineItem($this->product);

        $invoice = $this->modifier->processRules($invoice);

        $totalCost = self::COST + self::COST / 2 + self::COST;

        $this->assertSame($totalCost, $invoice->getTotal());    
    }

    /**
     * Tests that when there are enough products, the discount is
     * applied the correct number of times
     */
    public function testMultipleDiscounts(): void
    {
        $invoice = (new SimpleInvoice())
            ->addProductLineItem($this->product)
            ->addProductLineItem($this->product)
            ->addProductLineItem($this->product)
            ->addProductLineItem($this->product);

        $invoice = $this->modifier->processRules($invoice);

        $totalCost =
            self::COST + self::COST / 2 + self::COST + self::COST / 2;

        $this->assertSame($totalCost, $invoice->getTotal());    
    }
}