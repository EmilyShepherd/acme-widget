<?php

include __DIR__ . '/vendor/autoload.php';

use AcmeWidget\App;

$basket = App::getBasket();

while (true)
{
    echo "Enter a product To Add, or blank to see your total\n";
    $product = readline('> ');

    if ($product)
    {
        $basket->addProduct($product);
        echo "Added!\n\n";
    }
    else
    {
        printInvoice($basket->getInvoice());
        exit;
    }
}

/**
 * Prints out a price, given in cents, in a correctly padded human
 * readable dollar notation $yy.xx
 *
 * @param int $cents The raw price in cents
 * @return string The nicely formatted price
 */
function centsToDollar(int $cents): string
{
    $left = floor($cents / 100);
    $right = str_pad($cents - $left * 100, 2, '0');

    return '$' . $left . '.' . $right;
}

/**
 * Prints a single line item
 */
function printLineItem(array $item): void
{
    $desc = $item['description'];
    $cost = centsToDollar($item['cost']);

    echo "  * $desc - $cost\n";
}

/**
 * Prints the invoice total
 */
function printTotal(int $cost): void
{
    $total = centsToDollar($cost);
    echo "  -------------------------------\n";
    echo "    TOTAL    $total\n";
}

/**
 * Prints the invoice to stdout
 */
function printInvoice($invoice): void
{
    echo "\nHere's your invoice:\n";

    foreach ($invoice->getLineItems() as $item)
    {
        printLineItem($item);
    }

    printTotal($invoice->getTotal());
}