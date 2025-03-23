<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use OpenApi\Annotations as OA;

use App\Entity\Product;
use App\Form\ProductType;

class AdminController extends AbstractController
{
    
    /**
    * @Route("/admin", name="admin")
    * @OA\Post(
    *     path="/admin",
    *     tags={"Admin"},
    *     summary="Ajouter un produit",
    *     description="Affiche la page d'administration permettant d'ajouter et de gérer les produits.",
    *     responses={
    *         @OA\Response(
    *             response=200,
    *             description="Page d'administration affichée"
    *         )
    *     }
    * )
    */
    public function add(Request $request, ManagerRegistry $manager): Response
    {

        $newproduct = new Product;
        $products = $manager->getRepository(Product::class)->findAll();

        //Création d'un formulaire pour ajouter un produit
        $formAdd = $this->createForm(ProductType::class, $newproduct);
        $formAdd->handleRequest($request);
        if ($formAdd->isSubmitted() && $formAdd->isValid()) {
            $om = $manager->getManager();
            $om->persist($newproduct);
            $om->flush();
            return $this->redirectToRoute('admin');
        }

        $productForms = [];
        foreach ($products as $product) {
            $form = $this->createForm(ProductType::class, $product, [
                'action' => $this->generateUrl('update_product', ['id' => $product->getId()]),
                'method' => 'POST'
            ]);
            $productForms[$product->getId()] = $form->createView();
        }

        return $this->render('admin/add_product.html.twig', [
            'formAdd' => $formAdd->createView(),
            'productForms' => $productForms,
            'products' => $products,
        ]);
    }
}
