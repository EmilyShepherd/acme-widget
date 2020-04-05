<?php

namespace AcmeWidget\Test\Entities;

use AcmeWidget\App;
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
        $this->basket = App::getBasket();
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