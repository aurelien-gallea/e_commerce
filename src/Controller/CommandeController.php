<?php

namespace App\Controller;


use App\Entity\User;
use App\Service\PanierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;

class CommandeController extends AbstractController
{

    public function __construct(
        private Security $security, 
        private PanierService $panierService,
        private EntityManagerInterface $em
     ) {}

    #[route('/panier', name : 'app_panier_show')]
    public function show(Session $session) {
        $panier = $session->get('panier');
        
        // dd($user->getId());
        // dd($panier);
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'CommandeController',
            'panier' => $panier
        ]);
    } 

    #[route('/panier/{id}', name : 'app_panier_add')]
    public function addProduct(int $id, Session $session): Response
    {
        $this->panierService->addItem($id, $session);
        return $this->redirectToRoute('app_index');
    }

    #[route('/panier/delete/{key}', name : 'app_panier_delete')]
    public function delete(int $key, Session $session) : Response {
        $this->panierService->removeItem($key, $session);
        return $this->redirectToRoute('app_panier_show');

    }

    #[Route('/commande', name: 'app_commande_confirm')]
    public function confirm(Session $session): Response
    {
        $email = $this->security->getUser()->getUserIdentifier();
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        $this->panierService->confirmation($session, $user);
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}
