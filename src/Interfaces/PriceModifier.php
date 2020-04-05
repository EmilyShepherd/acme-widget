<?php

namespace AcmeWidget\Interfaces;

/**
 * This service should be used to modify the overall basket price based
 * on a business rule
 */
interface PriceModifier
{
    /**
     * Runs on the given basket and modifies the given involce based on
     * the business rules of the modifier
     *
     * @param Invoice $invoice
     * @return Invoice
     */
    public function processRules(Invoice $invoice): Invoice;
}