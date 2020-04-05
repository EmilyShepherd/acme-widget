<?php

namespace AcmeWidget\Services;

use AcmeWidget\Interfaces\Basket;
use AcmeWidget\Interfaces\Invoice;
use AcmeWidget\Interfaces\PriceModifier;

/**
 * Adds the delivery fee to the order
 */
class DeliveryFee implements PriceModifier
{
    /**
     * An array of threshold values and the delivery fee of the order if
     * the total is less than this threshold
     *
     * @var array
     */
    private $thresholds = [ ];

    /**
     * Sets up the DeliveryFee modifier with a list of thresholds
     *
     * @param array $thresholds
     */
    public function __construct(array $thresholds)
    {
        $this->thresholds = $thresholds;
    }

    /**
     * @inheritDoc
     */
    public function processRules(Invoice $invoice): Invoice
    {
        $totalPrice = $invoice->getTotal();
        $delivery = 0;

        foreach ($this->thresholds as $threshold => $cost)
        {
            if ($totalPrice < $threshold)
            {
                $delivery = $cost;
                break;
            }
        }

        return $invoice->addLineItem('Delivery Fee', $delivery);
    }
}