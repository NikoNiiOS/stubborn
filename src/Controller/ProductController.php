<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use OpenApi\Attributes as OA;

use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    /**
     * Permet de récupérer tous les produits
     */
    #[Route('/product', name: 'app_product', methods: ["GET"])]
    #[OA\Response(
        response: 200,
        description: 'Returns all the products',
    )]
    #[OA\Tag(name: 'Product')]

    public function index(ManagerRegistry $manager): Response
    {

        $products = $manager->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * Permet de récupérer un produit par son id.
     */

    #[Route('/product/{id}', name: 'product_by_id', methods: ["GET"])]
    #[OA\Response(
        response: 200,
        description: 'Returns one products by its id',
    )]
    #[OA\Tag(name: 'Product')]
    public function productById(int $id, ManagerRegistry $manager): Response
    {
        $product = $manager->getRepository(Product::class)->findOneBy(['id' => $id]);
        return $this->render('product/details.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Permet de récupérer les produits via une fourchette de prix.
     */

    #[Route("/filter-products", name: "filter_products", methods: ["GET"])]
    #[OA\Response(
        response: 200,
        description: 'Returns the products according to their price',
    )]
    #[OA\Tag(name: 'Product')]
    public function filterProducts(Request $request, ProductRepository $productRepository)
    {
        $priceRange = (int) $request->query->get('priceRange');

        switch ($priceRange) {
            case 1:
                $products = $productRepository->findByPriceRange(10, 30);
                break;
            case 2:
                $products = $productRepository->findByPriceRange(30, 35);
                break;
            case 3:
                $products = $productRepository->findByPriceRange(35, 50);
                break;
            default:
                $products = $productRepository->findAll();
        }

        $response = [];
        foreach ($products as $product) {
            $response[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'imageName' => $product->getImageName()
            ];
        }

        return new JsonResponse($response);
    }
}
