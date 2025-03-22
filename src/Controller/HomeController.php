<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Product;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function home(ManagerRegistry $manager): Response
    {
        $homepageProducts = $manager->getRepository(Product::class)->findBy(array('homepage'=>true));
        return $this->render('home/index.html.twig', [
            'homepageProducts' => $homepageProducts,
        ]);
    }
}
