<?php

namespace AcmeWidget;

use AcmeWidget\Services\SimpleBasket;
use AcmeWidget\Entities\StandardProduct;
use AcmeWidget\Services\DeliveryFee;
use AcmeWidget\Services\BuyXGetYDiscount;
use AcmeWidget\Entities\ArrayCatalog;

/**
 * Small bootstrap static class to create the product catalog, rules,
 * and basket needed by the app
 */
class App
{
    /**
     * Returns the catalog as given in the spec
     *
     * @return ArrayCatalog
     */
    public static function getProductCatalog(): ArrayCatalog
    {
        return new ArrayCatalog([
            new StandardProduct('Red Widget', 'R01', 3295),
            new StandardProduct('Green Widget', 'G01', 2495),
            new StandardProduct('Blue Widget', 'B01', 795),
        ]);
    }

    /**
     * Returns the Basket instance for use by the app
     *
     * @return SimpleBasket
     */
    public static function getBasket(): SimpleBasket
    {
        $catalog = self::getProductCatalog();

        return new SimpleBasket(
            $catalog,
            [
                new BuyXGetYDiscount($catalog->getProduct('R01'), 1, 50),
                new DeliveryFee([
                    5000 => 495, // Deliveries less than $50 are $4.95
                    9000 => 295, // Deliveries less than $90 are $2.95
                                 // Deliveries above are zero
                ]),
            ]
        );
    }
}