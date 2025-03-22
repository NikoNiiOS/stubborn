<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    private RequestStack $requestStack;
    private EntityManagerInterface $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function addToCart(int $id, string $size): void
    {
        $cart = $this->getSession()->get('cart', []);

        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $id && $item['size'] == $size) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'id' => $id,
                'size' => $size,
                'quantity' => 1,
            ];
        }

        $this->getSession()->set('cart', $cart);
    }


    public function removeOne(int $id, string $size): void
    {
        $cart = $this->getSession()->get('cart', []);

        foreach ($cart as $key => &$item) {
            if ($item['id'] == $id && $item['size'] == $size) {
                if ($item['quantity'] == 1) {
                    unset($cart[$key]);
                } else {
                    $item['quantity']--;
                }
                break;
            }
        }

        $this->getSession()->set('cart', $cart);
    }

    public function removeFromCart(int $id, string $size): void
    {
        $cart = $this->getSession()->get('cart', []);

        foreach ($cart as $key => $item) {
            if ($item['id'] == $id && $item['size'] == $size) {
                unset($cart[$key]);
                break;
            }
        }

        $this->getSession()->set('cart', $cart);
    }

    public function removeAll()
    {
        return $this->getSession()->remove('cart');
    }

    public function getTotal(): array
    {
        $cart = $this->getSession()->get('cart', []);
        $cartData = [];

        if ($cart) {
            foreach ($cart as $item) {
                if (!is_array($item) || !isset($item['id'])) {
                    continue;
                }

                $product = $this->em->getRepository(Product::class)->findOneBy(['id' => $item['id']]);
                if (!$product) {
                    continue;
                }
                $cartData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                ];
            }
            // dd($cartData);
        }

        return $cartData;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}