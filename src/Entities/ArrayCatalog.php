<?php

namespace AcmeWidget\Entities;

use AcmeWidget\Interfaces\Product;
use AcmeWidget\Interfaces\ProductCatalog;

/**
 * A ProductCatalog which simply holds a previously loaded array of
 * Products
 */
class ArrayCatalog implements ProductCatalog
{
    /**
     * The list of products
     */
    private $products = [ ];

    /**
     * Takes an array of products and indexes them into the store
     *
     * @param array $list
     */
    public function __construct(array $list)
    {
        foreach ($list as $product)
        {
            $this->products[$product->getProductCode()] = $product;
        }
    }

    /**
     * @inheritDoc
     */
    public function getProduct(string $code): ?Product
    {
        return $this->products[$code] ?? null;
    }
}