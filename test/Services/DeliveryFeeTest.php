<?php

namespace AcmeWidget\Test\Entities;

use AcmeWidget\Services\DeliveryFee;
use AcmeWidget\Entities\SimpleInvoice;
use PHPUnit\Framework\TestCase;

/**
 * Simple set of unit tests to ensure the DeliveryFee modifier works as
 * expected
 */
class DeliveryFeeTest extends TestCase
{
    /**
     * An instance of the DeliveryFee modifier to test
     *
     * @var DeliveryFee
     */
    private $deliveryFee;

    /**
     * Sets up each test by creating a DeliveryFee modifier with the
     * sample thresholds given in the spec
     */
    public function setUp(): void
    {
        $this->deliveryFee = new DeliveryFee([
            5000 => 495,
            9000 => 295,
        ]);
    }

    /**
     * Tests the order with various costs to ensure the correct delivery
     * fee is added
     *
     * @dataProvider deliveryTestProvider
     * @param int $cost The invoice cost pre delivery to test with
     * @param int $fee The expected delivery fee
     */
    public function testFee(int $cost, int $fee): void
    {
        $items = $this->deliveryFee->processRules(
            (new SimpleInvoice())->addLineItem('Test', $cost)
        )->getLineItems();

        $this->assertCount(2, $items);
        $this->assertSame('Delivery Fee', $items[1]['description']);
        $this->assertSame($fee, $items[1]['cost']);
    }

    /**
     * Provides test data for the fee test (testFee above)
     *
     * @return array
     */
    public function deliveryTestProvider(): array
    {
        return [
            [1000, 495], // Orders under $50 => delivery is $4.25
            [8100, 295], // Orders under $90 => delivery is $2.95
            [10600, 0], // Orders over $90 => delivery is free
            [9000, 0], // Orders of exactly $90 => delivery is free
        ];
    }
}