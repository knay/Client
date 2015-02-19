<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LigneProduitController extends Controller
{
    public function ajoutLigneProduitAction()
    {
        $soap = $this->get('noyau_soap');
        $return = $soap->call('getLigneProduit',array('count' => 0,'offset' => 0, 'nom' => ''));
        $liste_ligne_produit = json_decode($return);
        return $this->render('ImerirProduitBundle::ajoutLigneProduit.html.twig', array('liste_ligne_produit'=>$liste_ligne_produit));
    }

    public function execAjoutLigneProduitAction()
    {
        //TODO ajouter un service qui gere le client soap

        $query = $this->get('request');
        $nom = $query->request->get('nom');

        $soap = $this->get('noyau_soap');
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        $return = $soap->call('ajoutLigneProduit',array('nom' => $nom));

        $return_lp = $soap->call('getLigneProduit',array('count' => 0,'offset' => 0, 'nom' => ''));
        $liste_ligne_produit = json_decode($return_lp);
        return $this->render('ImerirProduitBundle::ajoutLigneProduit.html.twig', array('liste_ligne_produit'=>$liste_ligne_produit));
    }
}
