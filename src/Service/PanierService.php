<?php

namespace App\Service;

use DateTime;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService {

    public function __construct(private EntityManagerInterface $em) {}

    public function addItem(int $idProduit,  SessionInterface $session ) {
      
            $produit = $this->em->getRepository(Produit::class)->find($idProduit);
            $data = ['produit' => $produit,  'quantite' => 1];
            $lignesPanier = $session->get('panier');
            
            if (!$lignesPanier) {
                $lignesPanier = [];
            }
            
            $lignesPanier[] = $data;
            $session->set('panier', $lignesPanier);
        
    }

    public function removeItem(int $index,  SessionInterface $session) {
        $lignesPanier = $session->get('panier');
        
        array_splice($lignesPanier, $index, 1);
        $session->set('panier', $lignesPanier);
    }

    public function confirmation(SessionInterface $session, Client $user) {
        $data = $session->get('panier');
       
        $commande = new Commande();
        $commande->setClient($user);
        $commande->setDateCommande(new DateTime());
        $this->em->persist($commande);
        
        

        foreach ($data as $d) {
            $ligneCmd = new LigneCommande();
            $ligneCmd->setProduit($d['produit']);
            $ligneCmd->setQteCommandee($d['quantite']);
            $ligneCmd->setCommande($commande);
            
            $this->em->persist($ligneCmd);
        }
        $this->em->flush();
        $session->set('panier', []);
    }
}