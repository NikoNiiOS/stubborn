<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\StripeService;

class PaymentControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $stripeServiceMock = $this->createMock(StripeService::class);
      
        $fakeCheckoutUrl = 'https://stripe.com/checkout-session';
 
        $stripeServiceMock->method('checkout')
                          ->willReturn((object) ['url' => $fakeCheckoutUrl]);

        $client->getContainer()->set(StripeService::class, $stripeServiceMock);

        $client->request('POST', '/stripe', ['totalAmount' => 100]);

        $this->assertTrue($client->getResponse() instanceof RedirectResponse);
        $this->assertEquals($fakeCheckoutUrl, $client->getResponse()->headers->get('Location'));
    }
}
