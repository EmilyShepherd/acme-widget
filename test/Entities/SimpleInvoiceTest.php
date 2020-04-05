<?php

namespace AcmeWidget\Test\Entities;

use AcmeWidget\Entities\SimpleInvoice;
use AcmeWidget\Entities\StandardProduct;
use PHPUnit\Framework\TestCase;

/**
 * Simple set of unit tests to ensure the SimpleInvoice adds line items
 * and updates its total as expected, but remains immutable.
 */
class SimpleInvoiceTest extends TestCase
{
    /**
     * Tests that invoices start with no line items and cost
     */
    public function testStartBlank(): void
    {
        $invoice = new SimpleInvoice();

        $this->assertSame(0, $invoice->getTotal());
        $this->assertEmpty($invoice->getLineItems());
    }

    /**
     * Tests that the name sets expected
     */
    public function testImmutable(): void
    {
        $invoice = new SimpleInvoice();
        $initialTotal = $invoice->getTotal();

        $newInvoice = $invoice->addLineItem('Test Item', 1000);

        $this->assertNotSame($invoice, $newInvoice);
        $this->assertSame($initialTotal, $invoice->getTotal());
    }

    /**
     * Tests that adding a line item is reflected in the output line
     * items
     */
    public function testAddLineItem(): void
    {
        $invoice = (new SimpleInvoice())->addLineItem(
            'Test',
            300
        );

        $items = $invoice->getLineItems();

        $this->assertCount(1, $items);

        // Confirm it returns a non-associative array
        $this->assertTrue(isset($items[0]));

        $this->assertTrue(isset($items[0]['description']));
        $this->assertSame('Test', $items[0]['description']);
        $this->assertTrue(isset($items[0]['cost']));
        $this->assertSame(300, $items[0]['cost']);
    }

    /**
     * Tests that additional information is added onto the invoice
     */
    public function testAdditionalInformation(): void
    {
        $invoice = (new SimpleInvoice())->addLineItem(
            'Test',
            300,
            [
                'foo' => 'bar',
                'foobar' => 'test',
            ]
        );
        $item = $invoice->getLineItems()[0];

        $this->assertTrue(isset($item['foo']));
        $this->assertSame('bar', $item['foo']);
        $this->assertTrue(isset($item['foobar']));
        $this->assertSame('test', $item['foobar']);
    }

    /**
     * Tests that adding a product line item adds the line item in the
     * desired format
     */
    public function testAddProductLineItem(): void
    {
        $product = new StandardProduct('Test', 'T01', 400);
        $item = (new SimpleInvoice())
            ->addProductLineItem($product)
            ->getLineItems()[0];

        $this->assertSame('Test (T01)', $item['description']);
        $this->assertSame(400, $item['cost']);
        $this->assertTrue(isset($item['product']));
        $this->assertSame($product, $item['product']);
    }

    /**
     * Tests adding line items, updates the total
     */
    public function getCorrectTotal(): void
    {
        $invoice = (new SimpleInvoice())
            ->addLineItem('Test1', 200)
            ->addLineItem('Test2', 300);

        $this->assertSame(500, $invoice->getTotal());
    }
}