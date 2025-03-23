<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Attributes as OA;

use App\Service\CartService;

class CartController extends AbstractController
{
    //#[OA\Get(
        //path: '/cart',
        //tags: ['Cart'],
        //summary: 'Récupérer le contenu du panier',
        //description: 'Retourne le contenu du panier et le total des produits.',
        //responses: [
            //new OA\Response(
                //response: 200,
                //description: 'Retourne le contenu du panier'
            //)
        //]
    //)]
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getTotal(),
        ]);
    }

    //#[OA\Post(
        //path: '/cart/add/{id}',
        //tags: ['Cart'],
        //summary: 'Ajouter un produit au panier',
        //description: 'Ajoute un produit au panier selon l\'ID et la taille spécifiée.',
        //parameters: [
            //new OA\Parameter(
                //name: 'id',
                //in: 'path',
                //required: true,
                //description: 'Identifiant unique du produit',
                //schema: new OA\Schema(type: 'integer')
            //),
            //new OA\Parameter(
                //name: 'size',
                //in: 'query',
                //required: true,
                //description: 'Taille du produit',
                //schema: new OA\Schema(type: 'string')
            //)
        //],
        //responses: [
            //new OA\Response(
                //response: 200,
                //description: 'Produit ajouté au panier'
            //)
        //]
    //)]
    /**
     * @Route("/cart/add/{id<\d+>}", name="cart_add")
     */
    public function add(Request $request, CartService $cartService, int $id): Response
    {
        $size = $request->request->get('size');
        $cartService->addToCart($id, $size);

        return $this->redirectToRoute('app_cart', [
            'controller_name' => 'CartController',
        ]);
    }

    //#[OA\Post(
        //path: '/cart/removeOne/{id}',
        //tags: ['Cart'],
        //summary: 'Retirer une unité d\'un produit du panier',
        //description: 'Retire une unité d\'un produit du panier selon l\'ID et la taille spécifiée.',
        //parameters: [
            //new OA\Parameter(
                //name: 'id',
                //in: 'path',
                //required: true,
                //description: 'Identifiant unique du produit',
                //schema: new OA\Schema(type: 'integer')
            //),
            //new OA\Parameter(
                //name: 'size',
                //in: 'query',
                //required: true,
                //description: 'Taille du produit',
                //schema: new OA\Schema(type: 'string')
            //)
        //],
        //responses: [
            //new OA\Response(
                //response: 200,
                //description: 'Une unité du produit retirée du panier'
            //)
        //]
    //)]
    /**
     * @Route("/cart/removeOne/{id<\d+>}", name="cart_remove_one")
     */
    public function removeOne(Request $request, CartService $cartService, int $id): Response
    {
        $size = $request->request->get('size');
        $cartService->removeOne($id, $size);

        return $this->redirectToRoute('app_cart', [
            'controller_name' => 'CartController',
        ]);
    }

    //#[OA\Post(
        //path: '/cart/remove/{id}',
        //tags: ['Cart'],
        //summary: 'Retirer un produit du panier',
        //description: 'Retire un produit du panier selon l\'ID et la taille spécifiée.',
        //parameters: [
            //new OA\Parameter(
                //name: 'id',
                //in: 'path',
                //required: true,
                //description: 'Identifiant unique du produit',
                //schema: new OA\Schema(type: 'integer')
            //),
            //new OA\Parameter(
                //name: 'size',
                //in: 'query',
                //required: true,
                //description: 'Taille du produit',
                //schema: new OA\Schema(type: 'string')
            //)
        //],
        //responses: [
            //new OA\Response(
                //response: 200,
                //description: 'Produit retiré du panier'
            //)
        //]
    //)]
    /**
     * @Route("/cart/remove/{id<\d+>}", name="cart_remove")
     */
    public function removeFromCart(Request $request, CartService $cartService, int $id): Response
    {
        $size = $request->request->get('size');

        $cartService->removeFromCart($id, $size);

        return $this->redirectToRoute('app_cart', [
            'controller_name' => 'CartController',
        ]);
    }

    //#[OA\Post(
        //path: '/cart/removeAll',
        //tags: ['Cart'],
        //summary: 'Vider le panier',
        //description: 'Retire tous les produits du panier.',
        //responses: [
            //new OA\Response(
                //response: 200,
                //description: 'Tous les produits ont été retirés du panier'
            //)
        //]
    //)]
    /**
     * @Route("/cart/removeAll", name="cart_removeAll")
     */
    public function removeAll(CartService $cartService): Response
    {
        $cartService->removeAll();

        return $this->redirectToRoute('app_product', [
            'controller_name' => 'CartController',
        ]);
    }
}
