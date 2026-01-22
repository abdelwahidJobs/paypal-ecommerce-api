<?php

namespace App\Http\Controllers;

use PayPal\Checkout\Orders\ApplicationContext;
use PayPal\Checkout\Orders\Item;
use PayPal\Checkout\Orders\Order;
use PayPal\Checkout\Orders\PurchaseUnit;

const CAPTURE = 'CAPTURE';

class CartOrder extends Order
{
    public function __construct(array $invoice)
    {
        parent::__construct(CAPTURE);
        $this->application_context = new ApplicationContext();
        $this->application_context->setBrandName('Abdelwahid Inc');
        $this->application_context->setShippingPreference('NO_SHIPPING');
        $this->application_context->setUserAction('PAY_NOW');
        $this->application_context->setReturnUrl(config('paypal.return_url'));
        $this->application_context->setCancelUrl(config('paypal.cancel_url'));

        $this->buildPurchaseUnitFromInvoice($invoice);
    }

    private function buildPurchaseUnitFromInvoice(array $invoice): void
    {
        $currency_code = 'USD';
        $purchaseUnit = new PurchaseUnit($currency_code, ($invoice['totalCostCents']/100));
        $item1 = new Item('Booking Cost', $currency_code, ($invoice['totalCostCents']/100), 1);
        $purchaseUnit->addItem($item1);
        $this->addPurchaseUnit($purchaseUnit);
    }
}
