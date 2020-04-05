<?php

namespace AcmeWidget\Interfaces;

/**
 * Represents a basket which holds items on order
 */
interface Basket
{
    /**
     * Sets up the Basket with the ProductCatalog and an array of
     * PriceModifier rules
     */
    public function __construct(ProductCatalog $catalog, array $modifiers);

    /**
     * Adds a product to the basket, using its product code to look it
     * up
     *
     * @param string $productCode The code of the product to add to the
     *      basket
     */
    public function addProduct(string $procuctCode): void;

    /**
     * Gets the total of the basket
     *
     * @return int The total in cents
     */
    public function getTotal(): int;
}