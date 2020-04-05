<?php

namespace AcmeWidget\Interfaces;

/**
 * Represents a list of Products
 */
interface ProductCatalog
{
    /**
     * Searches for a Product by its product code
     *
     * @param string $code The code of the product you want to find
     * @return ?Product The Product if found, or null
     */
    public function getProduct(string $code): ?Product;
}