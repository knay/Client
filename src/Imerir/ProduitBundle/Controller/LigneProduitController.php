<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LigneProduitController extends Controller
{
    public function ajoutLigneProduitAction()
    {

        return $this->render('ImerirProduitBundle::ajoutLigneProduit.html.twig', array('utilisateur' => 'jojo','groupe' => 'toto'));
    }

    public function execAjoutLigneProduitAction()
    {
        //TODO ajouter un service qui gere le client soap

        $query = $this->get('request');
        $nom = $query->request->get('nom');

        $soap = $this->get('noyau_soap');
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        $return = $soap->call('ajoutLigneProduit',array('nom' => $nom));

        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        return $this->render('ImerirProduitBundle::ajoutLigneProduit.html.twig', array('utilisateur' => 'jojo','groupe' => 'toto'));
    }

}
