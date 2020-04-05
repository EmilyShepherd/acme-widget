<?php

namespace AcmeWidget\Interfaces;

/**
 * Represents an invoice of one or more line items
 *
 * Invoices should be immutable. That is when a line item is added, a
 * copy of the invoice should be made with the new line item on it.
 */
interface Invoice
{
    /**
     * Creates a copy of this invoice with the added line item
     *
     * @param string $description A human readable line to describe the
     *      line item
     * @param string $cost The cost of the line item, in cents
     */
    public function addLineItem(string $description, int $cost): self;

    /**
     * Returns the list of line items with their costs
     *
     * @return array
     */
    public function getLineItems(): array;

    /**
     * Returns the total cost of the invoice
     *
     * @return int The cost in cents
     */
    public function getTotal(): int;
}