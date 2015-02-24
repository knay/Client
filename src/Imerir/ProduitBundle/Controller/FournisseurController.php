<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FournisseurController extends Controller
{
    public function ajoutFournisseurAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');
        //TODO ajouter un champs post dans le twig avec recherche fournisseur
        $recherche_fournisseur = $query->request->get('recherche_fournisseur');

        if($recherche_fournisseur===null)
            $nom='';
        else
            $nom=$recherche_fournisseur;

        //TODO ajouter l'action getFournisseurs qui renvoie le nom, le mail et le num de tel
        $return = $soap->call('getLigneProduit',array('count' => 0,'offset' => 0, 'nom' => $nom));
        $liste_ligne_produit = json_decode($return);

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //TODO invoquer le bon twig
        return $this->render('ImerirProduitBundle::ajoutLigneProduit.html.twig', array('liste_ligne_produit'=>$liste_ligne_produit,'result_menu' => $menu_sous_menu));
    }
}
