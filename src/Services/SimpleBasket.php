<?php

namespace AcmeWidget\Services;

use AcmeWidget\Interfaces\Basket;
use AcmeWidget\Interfaces\ProductCatalog;
use AcmeWidget\Entities\SimpleInvoice;

/**
 * An implementation of the Basket interface which adds items from the
 * given ProductCatalog to a SimpleInvoice, and modifies this at the end
 * with a list of PriceModifiers
 */
class SimpleBasket implements Basket
{
    /**
     * The ProductCatalog this Basket uses to find items
     *
     * @var ProductCatalog
     */
    private $catalog;

    /**
     * An array of PriceModifiers that are applied at the end when
     * drawing up an invoice
     *
     * @array
     */
    private $modifiers;

    /**
     * An array of Product items which are in the basket
     *
     * @var SimpleInvoice
     */
    private $invoice;

    /**
     * Sets up the Basket with the ProductCatalog and any required
     * modifiers
     */
    public function __construct(
        ProductCatalog $catalog,
        array $modifiers
    ) {
        $this->catalog = $catalog;
        $this->modifiers = $modifiers;
        $this->invoice = new SimpleInvoice();
    }

    /**
     * @inheritDoc
     */
    public function addProduct(string $productCode): void
    {
        $product = $this->catalog->getProduct($productCode);

        if (!$product)
        {
            throw new \Exception('Cannot find ' . $productCode);
        }

        $this->invoice = $this->invoice->addProductLineItem($product);
    }

    /**
     * Creates a SimpleInvoice from the Products that it has in its
     * basket, and applies any PriceModifiers at the end
     *
     * @return SimpleInvoice
     */
    public function getInvoice() : SimpleInvoice
    {
        $invoice = $this->invoice;

        // For any price modifiers we have, apply these
        foreach ($this->modifiers as $modifier)
        {
            $invoice = $modifier->processRules($invoice);
        }

        return $invoice;
    }

    /**
     * @inheritDoc
     */
    public function getTotal(): int
    {
        return $this->getInvoice()->getTotal();
    }
}