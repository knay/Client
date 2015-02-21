<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LigneProduitController extends Controller
{
    public function ajoutLigneProduitAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');
        $recherche_lp = $query->request->get('recherche_lp');

        if($recherche_lp===null)
            $nom='';
        else
            $nom=$recherche_lp;

        $return = $soap->call('getLigneProduit',array('count' => 0,'offset' => 0, 'nom' => $nom));
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
        return $this->render('ImerirProduitBundle::ajoutLigneProduit.html.twig', array('liste_ligne_produit'=>$liste_ligne_produit,'nom_lp_add'=>$nom));
    }

    public function modifLigneProduitAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');
        //on recuperer les variables en post ici variable pour filtrer le tableau
        //et variable pour modifier une ligne produit
        $recherche_lp = $query->request->get('recherche_lp');
        $nom_modif_lp = $query->request->get('nom_lp');
        $id_modif_lp = $query->request->get('id_lp');
        if($recherche_lp===null)
            $nom='';
        else
            $nom=$recherche_lp;

        $return = $soap->call('getLigneProduit',array('count' => 0,'offset' => 0, 'nom' => $nom));
        $liste_ligne_produit = json_decode($return);
        return $this->render('ImerirProduitBundle::ajoutLigneProduit.html.twig',
            array('liste_ligne_produit'=>$liste_ligne_produit,'lp_nom_val'=>$nom_modif_lp,'lp_id_val'=>$id_modif_lp));
    }

    public function execModifLigneProduitAction()
    {

        $query = $this->get('request');
        $id = $query->request->get('id_lp');
        $nom = $query->request->get('nom');
        $old_nom = $query->request->get('old_nom');

        $soap = $this->get('noyau_soap');
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        $return = $soap->call('modifLigneProduit',array('id' => $id,'nom'=>$nom));

        $return_lp = $soap->call('getLigneProduit',array('count' => 0,'offset' => 0, 'nom' => ''));
        $liste_ligne_produit = json_decode($return_lp);
        return $this->render('ImerirProduitBundle::ajoutLigneProduit.html.twig',
            array('liste_ligne_produit'=>$liste_ligne_produit,'nom_lp_add'=>$nom,'old_nom_lp'=>$old_nom));
    }
}
