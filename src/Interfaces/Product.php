<?php

namespace AcmeWidget\Interfaces;

/**
 * Represents a product entity in the system
 */
interface Product
{
    /**
     * Returns the name of the product
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Returns the product code of the product
     *
     * @return string
     */
    public function getProductCode(): string;

    /**
     * Returns the base price, in cents, of the product
     *
     * @return int
     */
    public function getBasePrice(): int;
}