<?php

namespace Imerir\FournisseurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function ajoutFournisseurAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');
        //TODO ajouter un champs post dans le twig avec recherche fournisseur
        $recherche_fournisseur_nom = $query->request->get('recherche_fournisseur_nom');
        $recherche_fournisseur_email = $query->request->get('recherche_fournisseur_email');
        $recherche_fournisseur_telephone_portable = $query->request->get('recherche_fournisseur_telephone_portable');


        if($recherche_fournisseur_nom===null)
            $nom='';
        else
            $nom=$recherche_fournisseur_nom;
        if($recherche_fournisseur_email===null)
            $email='';
        else
            $email=$recherche_fournisseur_email;
        if($recherche_fournisseur_telephone_portable===null)
            $telephone_portable = '';
        else
            $telephone_portable = $recherche_fournisseur_telephone_portable;

        //TODO ajouter l'action getFournisseurs qui renvoie le nom, le mail et le num de tel
        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => $nom, 'email'=>$email, 'telephone_portable'=>$telephone_portable));
        $liste_fournisseurs = json_decode($return);

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //TODO invoquer le bon twig
        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu' => $menu_sous_menu));
    }

    public function execAjoutFournisseurAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');
        //TODO ajouter un champs post dans le twig avec recherche fournisseur
        $recherche_fournisseur_nom = $query->request->get('recherche_fournisseur_nom');
        $recherche_fournisseur_email = $query->request->get('recherche_fournisseur_email');
        $recherche_fournisseur_telephone_portable = $query->request->get('recherche_fournisseur_telephone_portable');


        if($recherche_fournisseur_nom===null)
            $nom='';
        else
            $nom=$recherche_fournisseur_nom;
        if($recherche_fournisseur_email===null)
            $email='';
        else
            $email=$recherche_fournisseur_email;
        if($recherche_fournisseur_telephone_portable===null)
            $telephone_portable = '';
        else
            $telephone_portable = $recherche_fournisseur_telephone_portable;

        //TODO ajouter l'action getFournisseurs qui renvoie le nom, le mail et le num de tel
        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => $nom, 'email'=>$email, 'telephone_portable'=>$telephone_portable));
        $liste_fournisseurs = json_decode($return);

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //TODO invoquer le bon twig
        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu' => $menu_sous_menu));
    }

}
