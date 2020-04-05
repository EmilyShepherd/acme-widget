<?php

namespace AcmeWidget\Entities;

use AcmeWidget\Interfaces\Invoice;

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
    public function addLineItem(string $description, int $cost): Invoice
    {
        $invoice = clone $this;

        $invoice->items[] = [
            'description' => $description,
            'cost' => $cost,
        ];
        $invoice->total += $cost;

        return $invoice;
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