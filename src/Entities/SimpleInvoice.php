<?php

namespace AcmeWidget\Entities;

use AcmeWidget\Interfaces\Invoice;
use AcmeWidget\Interfaces\Product;

/**
 * @inheritDoc
 */
class SimpleInvoice implements Invoice
{
    /**
     * The list of line items on this invoice
     *
     * @var array
     */
    private $items = [ ];

    /**
     * The total for this invoice
     *
     * @var int The total in cents
     */
    private $total = 0;

    /**
     * @inheritDoc
     */
    public function addLineItem(
        string $description,
        int $cost,
        array $additionalInfo = [ ]
    ): Invoice
    {
        $invoice = clone $this;

        $invoice->items[] = [
            'description' => $description,
            'cost' => $cost,
        ] + $additionalInfo;
        $invoice->total += $cost;

        return $invoice;
    }

    /**
     * Adds a product as a line item to the invoice
     *
     * @param $item Product
     * @return self
     */
    public function addProductLineItem(Product $item): Invoice
    {
        return $this->addLineItem(
            $item->getName() . ' (' . $item->getProductCode() . ')',
            $item->getBasePrice(),
            [
                'product' => $item
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @inheritDoc
     */
    public function getLineItems(): array
    {
        return $this->items;
    }
}