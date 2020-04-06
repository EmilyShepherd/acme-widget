# Acme Widget Co Sales Tool

This tool is a small proof of concept Sales Tool. It consists of a
simple basket of products, which produces an invoices of line items,
adding any discounts for multibuy offers and costs for delivery fees.

## General Structure

The General Structure of the application uses the following core
principles:

### Products

A `Product` has a human friendly name, a uniquely identifying product
code, and a base price, which is represented in cents. This system just
has a single simple implementation of this, called `StandardProduct`.

Products are grouped together and searched for via a `ProductCatalog`.
This application has an implementation of this called, the
`ArrayCatalog` which takes an array of `Product` entities. In theory,
you could implement the `ProductCatalog` with a service which queries a
database, or Salesforce, or any other data source. The system uses
interfaces, so swapping out the implementation of this would not require
rewriting any of the rest of the system.

### Basket and Invoices

An invoice is an _immutable_ list of line items. Line items in an
invoice can be anything, and as such are represented as arrays rather
than a more ridget data structure. The only requirement is that they
have "description" and "cost" keys, which the `SimpleInvoice`
implementation will always add. Invoices are immutable so that changes
can be tracked, either for auditing, or debugging the system.

The `Basket` is the mutable wrapper for the `Invoice` which allows items
to be added at will. It is responsible for looking up products and
adding these as line items to an invoice. Whenever an item is made, the
`Basket` will create a new `Invoice` with the new item on it, and update
its reference to that. The `SimpleBasket` implementation is also
responsible for applying business logic, such as delivery fees and offer
discounts. For this it uses a list of `PriceModifiers`.

### Price Modifiers

`PriceModifiers` are designed to accept an `Invoice` - they analyse its
contents, and add any line items they need to, returning a new `Invoice`
if they have made any changes. The two implementations of the
`PriceModifier` in this project are:

The `DeliveryFee` which is instantiated with a set of cost rules. It
looks at the overall cost of the invoice, and adds a delivery fee based
on its threshold rules. Anything over its highest threshold is deemed to
be free delivery.

The `BuyXGetYDiscount` modifier is applies multibuy discounts to orders.
It is instantiated with the product to look out for, the number of
occurances to look for, and what discount to apply to the next occurance
when it reaches its threshold.

For example, buy one get one half price is:
 * Look out for 1
 * Then apply a 50% discount to the next

Buy two, get the third three is:
 * Look out for 2
 * Then apply a 100% discount to the next

This modifier will add a "MultiBuy Discount for [Product Name]" line
item, with the negative cost of whatever the discount was, whereever
appropriate.

## Running the program

### In Command Line Mode

The program is a modelling proof of concept, so its interface is
limited, however it has a basic command line prompt interface.

It uses composer autoloading for convience - first run:

```
composer dumpautoload
```

*It has no dependencies for normal excecution, so composer install is
not required for the command line interface*.

To run the interface, run:

```
php app.php
```

### Running the tests

The system has 100% code coverage using PHPUnit. Most of tests are unit
tests, however `test/Services/SimpleBasketTest` includes overall tests
for the system as a whole (using the test data given in the spec).

PHPUnit is required to run, it can be added via composer:

```
composer install
```

Then run the tests with:

```
./vendor/bin/phpunit
```

## Assumptions

### Multiple offers

The system assumes that it is valid to use the same offer more than
once. For example, the offer coded into the system is: Buy 1 get 1 half
price for Red Widgets. So 2 Red Widgets would result in one 50% discount.
This can, however, be redeemed any number of times - that that is to say
an order of 4 red widgets would result in two 50% discounts.

### Offer Rounding

When applying discounts, the system assumes that any value less than a
cent should be rounded _down_. That is to say: a product of $9.99 with a
50% discount would result in a $4.99 discount being applied. It also
assumes that all rounding should happen atomically per discount, rather
than being added up with remainders and then rounded when totalled up.

Eg: 3 products of $9.99 each with 50% applied would result in three
$4.99 discounts, _not_ three $4.995 discounts which are rounded down
after being added together.

### Offer Products

The system's current implementation assumes that offers are only applied
to multiples of _the same product_. Offers like "buy x items, get the
cheapest free" are not currently supported. However, due to the
extensible nature of the `PriceModifier` modifier, such a rule could be
relatively easily applied.

### Overlapping Offers

The order that offers are applied currently matters. For example, it's
important that `DeliveryFee` is last in the list as it takes into
account the discounted price caused by the Buy One Get One Half Price.
Should lots of complex and overlapping offers be added to the system,
further work may be required to ensure the system can choose the correct
order / priority to apply price modifiers.

## Possible extensions

### JSON product list

The product list is currently hard coded into `App` for this PoC,
however this could relatively trivially be moved into a JSON file and
loaded in at runtime, to make editing easier. Of course, in time, this
would make sense to exist in a database.