<?php

namespace AcmeWidget\Test\Entities;

use AcmeWidget\Services\SimpleBasket;
use AcmeWidget\Entities\StandardProduct;
use AcmeWidget\Services\DeliveryFee;
use AcmeWidget\Services\BuyXGetYDiscount;
use AcmeWidget\Entities\ArrayCatalog;
use PHPUnit\Framework\TestCase;

/**
 * Set of tests to ensure the BuyXGetYDiscount PriceModifier works as
 * expected
 */
class SimpleBasketTest extends TestCase
{
    /**
     * An instance of a SimpleBasket to test
     *
     * @var SimpleBasket
     */
    private $basket;

    /**
     * Sets up each test by creating a StandardProduct for use in
     * testing
     */
    public function setUp(): void
    {
        $redWidget = new StandardProduct('Red Widget', 'R01', 3295);
        $this->basket = new SimpleBasket(
            new ArrayCatalog([
                $redWidget,
                new StandardProduct('Green Widget', 'G01', 2495),
                new StandardProduct('Blue Widget', 'B01', 795),
            ]),
            [
                new BuyXGetYDiscount($redWidget, 1, 50),
                new DeliveryFee([5000 => 495, 9000 => 295])
            ]
        );
    }

    /**
     * Tests that the overall basket price is as expected in the spec
     *
     * @dataProvider basketTestProvider
     */
    public function testBasketCase(array $products, int $cost): void
    {
        foreach ($products as $product)
        {
            $this->basket->addProduct($product);
        }

        $this->assertSame($cost, $this->basket->getTotal());
    }

    /**
     * Provides test basket data and the expected total cost for the
     * basket test (testBasketCase above)
     */
    public function basketTestProvider(): array
    {
        return [
            [['B01', 'G01'], 3785],
            [['R01', 'R01'], 5437],
            [['R01', 'G01'], 6085],
            [['B01', 'B01', 'R01', 'R01', 'R01'], 9827],
        ];
    }

    /**
     * Tests that trying to add a non existant product code results in
     * an error
     */
    public function testBadProductCode(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot find ERR01');

        $this->basket->addProduct('ERR01');
    }
}