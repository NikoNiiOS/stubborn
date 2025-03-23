<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testIndexPage()
    {
        $client = static::createClient();
        $client->request('GET', '/cart');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testAddPage()
    {
        $client = static::createClient();
        $size = 'S';
        $client->request('POST', '/cart/add/1', ['size' => $size]);
        $this->assertResponseRedirects();

        $crawler = $client->followRedirect();
        $session = $client->getRequest()->getSession();
        $cart = $session->get('cart');
        $total = $crawler->filter('.cart_total h3')->text();
        $this->assertNotEmpty($cart);
        $this->assertEquals(1, $cart[0]['id']);
        $this->assertEquals($size, $cart[0]['size']);
        $this->assertEquals(1, $cart[0]['quantity']);
        $this->assertEquals('Total : 29,90 €', $total);

        $size = 'S';
        $client->request('POST', '/cart/add/1', ['size' => $size]);
        $this->assertResponseRedirects();

        $client->followRedirect();
        $session = $client->getRequest()->getSession();
        $cart = $session->get('cart');
        $this->assertNotEmpty($cart);
        $this->assertEquals(1, $cart[0]['id']);
        $this->assertEquals($size, $cart[0]['size']);
        $this->assertEquals(2, $cart[0]['quantity']);

        $size = 'M';
        $client->request('POST', '/cart/add/1', ['size' => $size]);
        $this->assertResponseRedirects();

        $client->followRedirect();
        $session = $client->getRequest()->getSession();
        $cart = $session->get('cart');
        $this->assertNotEmpty($cart);
        $this->assertEquals(1, $cart[1]['id']);
        $this->assertEquals($size, $cart[1]['size']);
        $this->assertEquals(1, $cart[1]['quantity']);
    }

    public function testRemoveOnePage()
    {
        $client = static::createClient();
        $size = 'S';
        $client->request('POST', '/cart/add/1', ['size' => $size]);
        $client->request('POST', '/cart/add/1', ['size' => $size]);

        $client->request('POST', '/cart/removeOne/1', ['size' => $size]);

        $client->followRedirect();
        $session = $client->getRequest()->getSession();
        $cart = $session->get('cart');
        $this->assertNotEmpty($cart);
        $this->assertEquals(1, $cart[0]['id']);
        $this->assertEquals($size, $cart[0]['size']);
        $this->assertEquals(1, $cart[0]['quantity']);
    }

    public function testRemovePage()
    {
        $client = static::createClient();
        $size = 'S';
        $client->request('POST', '/cart/add/1', ['size' => $size]);
        $client->request('POST', '/cart/add/1', ['size' => $size]);

        $client->request('POST', '/cart/remove/1', ['size' => $size]);

        $client->followRedirect();
        $session = $client->getRequest()->getSession();
        $cart = $session->get('cart');
        $this->assertEmpty($cart);
    }

    public function testRemoveAllPage()
    {
        $client = static::createClient();
        $size = 'S';
        $client->request('POST', '/cart/add/1', ['size' => $size]);
        $client->request('POST', '/cart/add/2', ['size' => $size]);

        $client->request('POST', '/cart/removeAll');

        $client->followRedirect();
        $session = $client->getRequest()->getSession();
        $cart = $session->get('cart');
        $this->assertEmpty($cart);
    }
}
