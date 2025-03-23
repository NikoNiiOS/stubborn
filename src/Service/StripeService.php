<?php

namespace App\Service;

class StripeService
{
    private $privateKey;

    public function __construct()
    {
        $this->privateKey = $_ENV["STRIPE_SECRET"];
    }

    public function checkout($totalAmount)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:8000';

        return \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Total de votre commande'
                    ],
                    'unit_amount' => (int) $totalAmount * 100
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success',
            'cancel_url' => $YOUR_DOMAIN . '/cancel',
            'automatic_tax' => [
                'enabled' => true,
            ],
        ]);
    }
}