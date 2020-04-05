<?php

namespace AcmeWidget\Services;

use AcmeWidget\Interfaces\Product;
use AcmeWidget\Interfaces\Invoice;
use AcmeWidget\Interfaces\PriceModifier;

/**
 * This PriceModifier applies discounts based on Buy One Get One Free /
 * Buy Two Get One Half Price rules
 *
 * It has been abstracted to Buy X Get one for Y% off. So in the case of
 * BuyOneGetOneFree, X = 1, Y = 100. In the second example, X = 2,
 * Y = 50.
 */
class BuyXGetYDiscount implements PriceModifier
{
    /**
     * The product this discount applies to
     *
     * @var Product
     */
    private $product;

    /**
     * The number of items that need to be purchased for the discount to
     * be applied to the next one
     *
     * @var int
     */
    private $count;

    /**
     * The discount that is applied as a percentage of the price
     *
     * @var int
     */
    private $discount;

    /**
     * Sets up the BuyXGetYDiscount modifier with its parameters
     *
     * @param Product $product
     * @param int $count
     * @param int $discount
     */
    public function __construct(
        Product $product,
        int $count,
        int $discount
    ) {
        $this->product = $product;
        $this->count = $count;
        $this->discount =
            floor($product->getBasePrice() * -1 * ($discount / 100));
    }

    /**
     * @inheritDoc
     */
    public function processRules(Invoice $invoice): Invoice
    {
        $found = 0;

        foreach ($invoice->getLineItems() as $item)
        {
            // Check if this line item is the product this discount
            // covers 
            if ($item['product'] === $this->product)
            {
                // If this is a product we're interest in, we need to
                // increment the count of the number we've found.
                // If we've already reached the count we're looking for,
                // we then reset and add the discount line.
                if ($found++ === $this->count)
                {
                    $found = 0;
                    $invoice = $invoice->addLineItem(
                        'MultiBuy Discount For ' . $this->product->getName(),
                        $this->discount
                    );
                }
            }
        }

        return $invoice;
    }
}