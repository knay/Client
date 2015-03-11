<?php

namespace Imerir\FournisseurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommandesController extends Controller
{
    public function ajoutCommandeAction()
    {
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');
        //PARTIE RECHERCHE
        /*
        <label for="recherche_commande_fournisseur_nom_fournisseur">Nom fournisseur :</label>
        <input type="text" name="recherche_fournisseur_nom_fournisseur">
        <label for="recherche_fournisseur_article">Article :</label>
        <input type="text" name="recherche_fournisseur_article">
        <label for="recherche_commande_fournisseur_id">Numéro de commande :</label>
        <input type="text" name="recherche_commande_fournisseur_id">
        */
        $recherche_nom = $query->request->get('recherche_fournisseur_nom_fournisseur');
        $recherche_article = $query->request->get('recherche_fournisseur_article');
        $recherche_commande = $query->request->get('recherche_commande_fournisseur_id');

        if(!empty($recherche_nom))
            $nom = $recherche_nom;
        else
            $nom = '';
        if(!empty($recherche_article))
            $article = $recherche_article;
        else
            $article = '';
        if(!empty($recherche_commande))
            $commande = $recherche_commande;
        else
            $commande = '';
        //////////////////

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => '', 'email'=>'',
            'telephone_portable'=>'','reference_client'=>'','notes'=>''));
        $liste_fournisseurs = json_decode($return);

        $return_commandes = $soap->call('getCommandesFournisseurs',array('count' => 0,'offset' => 0,'fournisseur_id'=>'',
            'fournisseur_nom'=>$nom,'commande_id'=>$commande,'article_code'=>$article ));

        $liste_commandes = json_decode($return_commandes);
        return $this->render('ImerirFournisseurBundle::ajoutCommandeFournisseur.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0));
    }


    public function execAjoutCommandeAction(){
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');

        $commande_nom_fournisseur = $query->request->get('fournisseur');
        $commande_article = $query->request->get('article');
        $commande_qty = $query->request->get('qty');
        $commande_date = $query->request->get('date_commande');


        //$fournisseur_id, $article_code, $date_commande, $quantite_souhaite
        $soap->call('ajoutCommandeFournisseur',array('fournisseur_id'=>json_encode($commande_nom_fournisseur),
            'article_code'=>json_encode($commande_article)
        , 'date_commande'=>json_encode($commande_date),'quantite_souhaite'=>json_encode($commande_qty)));

        //PARTIE COMMUNE//////////////////////////////////////////
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => '', 'email'=>'',
            'telephone_portable'=>'','reference_client'=>'','notes'=>''));
        $liste_fournisseurs = json_decode($return);

        $return_commandes = $soap->call('getCommandesFournisseurs',array('count' => 0,'offset' => 0,'fournisseur_id'=>'',
            'fournisseur_nom'=>'','commande_id'=>'','article_code'=>'' ));

        $liste_commandes = json_decode($return_commandes);
        return $this->render('ImerirFournisseurBundle::ajoutCommandeFournisseur.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0));
        ////////////////////////////////////////////////////////////

    }

}
