<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    #[Route('/home', name: 'app_home')]

   
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Produit::class)->findAll();

        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }
}
