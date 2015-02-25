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

        $fournisseur_nom = $query->request->get('nom');
        $fournisseur_email = $query->request->get('email');
        $fournisseur_telephone_portable = $query->request->get('telephone_portable');

        if($fournisseur_nom===null)
            $fournisseur_nom='';

        if($fournisseur_email===null)
            $fournisseur_email='';

        if($fournisseur_telephone_portable===null)
            $fournisseur_telephone_portable===null;

        $soap->call('ajoutFournisseur',array('nom' => $fournisseur_nom, 'email'=>$fournisseur_email, 'telephone_portable'=>$fournisseur_telephone_portable));

        //TODO ajouter l'action getFournisseurs qui renvoie le nom, le mail et le num de tel
        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => '', 'email'=>'', 'telephone_portable'=>''));
        $liste_fournisseurs = json_decode($return);
        //insertion du fournisseur

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //TODO invoquer le bon twig
        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu' => $menu_sous_menu));
    }

    public function modifFournisseurAction(){
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');

        $modif_id = $query->request->get('id_f');
        $modif_nom = $query->request->get('nom_f');
        $modif_email = $query->request->get('email_f');
        $modif_telephone_portable = $query->request->get('telephone_portable_f');

        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => '', 'email'=>'', 'telephone_portable'=>''));
        $liste_fournisseurs = json_decode($return);

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);



        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu'=>$menu_sous_menu,'modif_id'=>$modif_id,
            'modif_nom'=>$modif_nom,'modif_email'=>$modif_email,
            'modif_telephone_portable'=>$modif_telephone_portable));

    }

    public function execModifFournisseurAction(){
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');

        $old_id = $query->request->get('old_id');
        $old_nom = $query->request->get('old_nom');
        $new_nom = $query->request->get('new_nom');
        $new_email = $query->request->get('new_email');
        $new_telephone_portable = $query->request->get('new_telephone_portable');

        $soap->call('modifFournisseur',array('id'=>$old_id,
            'nom'=>$new_nom,'email'=>$new_email,'telephone_portable'=>$new_telephone_portable));

        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => '', 'email'=>'', 'telephone_portable'=>''));
        $liste_fournisseurs = json_decode($return);

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu'=>$menu_sous_menu,'old_id'=>$old_id,
            'old_nom'=>$old_nom,'new_nom'=>$new_nom,'new_email'=>$new_email,
            'new_telephone_portable'=>$new_telephone_portable));

    }

}
