<?php

namespace AcmeWidget\Entities;

use AcmeWidget\Interfaces\Product;

/**
 * A standard product which does not do anything particular clever
 */
class StandardProduct implements Product
{
    /**
     * The name of the product
     * 
     * @var string
     */
    private $name;

    /**
     * The product code of the product
     *
     * @var string
     */
    private $productCode;

    /**
     * The base price of the product
     *
     * @var int
     */
    private $basePrice;

    /**
     * Sets up the StandardProduct with its name, product code, and
     * base price
     *
     * @param string $name
     * @param string $productCode
     * @param string $basePrice
     */
    public function __construct(
        string $name,
        string $productCode,
        string $basePrice
    ) {
        $this->name = $name;
        $this->productCode = $productCode;
        $this->basePrice = $basePrice;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getProductCode(): string
    {
        return $this->productCode;
    }

    /**
     * @inheritDoc
     */
    public function getBasePrice(): int
    {
        return $this->basePrice;
    }
}