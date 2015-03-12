<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ReceptionCommandeController extends Controller
{
    public function ajoutReceptionAction()
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

        $erreur = '';

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

        try {
            $return = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => '', 'email' => '',
                'telephone_portable' => '', 'reference_client' => '', 'notes' => ''));
            $liste_fournisseurs = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        try {
            //($count, $offset, $fournisseur_id, $fournisseur_nom, $commande_id, $article_code)
            $return_commandes = $soap->call('getCommandesFournisseurs', array('count' => 0, 'offset' => 0, 'fournisseur_id' => '',
                'fournisseur_nom' => $nom, 'commande_id' => $commande, 'article_code' => $article));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        $liste_commandes = json_decode($return_commandes);
        return $this->render('ImerirProduitBundle::ajoutReceptionCommande.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0,'erreur'=>$erreur,
            'flag_no_commande'=>''));
    }


    public function saisieReceptionAction(){
        //PARTIE COMMUNE////////
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');
        ///////////////////////

        $modif_fournisseur_id = $query->request->get('modif_fournisseur_id');
        $modif_date_commande = $query->request->get('modif_commande_date_commande');
        $modif_commande_id = $query->request->get('modif_commande_id');
        $modif_code_barre = $query->request->get('modif_code_barre');
        $modif_quantite_souhaite = $query->request->get('modif_quantite_souhaite');
        $modif_quantite_recu = $query->request->get('modif_quantite_recu');

        $erreur = '';

        try {
            $return_liste_lignes_commandes = $soap->call('getLignesCommandesFournisseurs', array('count' => 0, 'offset' => 0, 'fournisseur_id' => '',
                'fournisseur_nom' => '', 'commande_id' => $modif_commande_id, 'article_code' => ''));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        $liste_lignes_commandes = json_decode($return_liste_lignes_commandes);

        //PARTIE COMMUNE//////////////////////////////////////////
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);


        try {
            $return = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => '', 'email' => '',
                'telephone_portable' => '', 'reference_client' => '', 'notes' => ''));
            $liste_fournisseurs = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        try {
            $return_commandes = $soap->call('getCommandesFournisseurs', array('count' => 0, 'offset' => 0, 'fournisseur_id' => '',
                'fournisseur_nom' => '', 'commande_id' => '', 'article_code' => ''));

        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }
        $liste_commandes = json_decode($return_commandes);
        return $this->render('ImerirProduitBundle::ajoutReceptionCommande.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0,
            'liste_lignes_commandes'=>$liste_lignes_commandes,'modif_date_commande'=>$modif_date_commande,'erreur'=>$erreur));
        ////////////////////////////////////////////////////////////

    }


    public function execSaisieReceptionAction(){

        /*
         * <input type="hidden" name="modif_commande_id_{{ i.commande_id }}" value="{{ i.commande_id }}"/>
                            <input type="hidden" name="modif_ligne_commande_id_{{ i.ligne_commande_id }}" value="{{ i.ligne_commande_id }}"/>
                            <input class="est_visible" type="hidden" name="modif_est_visible_{{ i.commande_id }}" value="1"/>
                            <td><input type="hidden" name="modif_article_{{ i.commande_id }}" value="{{ i.code_barre }}"/>
                                <label for="modif_article">{{ i.code_barre }}</label></td>
                            <td><input type="hidden" name="modif_quantite_souhaite_{{ i.commande_id }}" value="{{ i.quantite_souhaite }}"/>
                                <label for="modif_quantite_souhaite">{{ i.quantite_souhaite}}</label></td>
                            </td>
                            <td>
                                <input type="number" name="quantite_recu">
         */
        //ajoutReceptionCommandeAction($commande_id, $article_code, $quantite, $date_reception)
        //PARTIE COMMUNE////////
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');
        ///////////////////////
        $modif_commande_id = array();
        $modif_ligne_commande_id = array();
        $modif_article = array();
        $modif_quantite = array();
        $modif_date_reception = array();

        $erreur = '';


        foreach ($query->request as $key => $value) {
            if (substr($key, 0, strlen('modif_ligne_commande_id')) === 'modif_ligne_commande_id') {
                array_push($modif_ligne_commande_id, $value);
            }
            if (substr($key, 0, strlen('modif_commande_id')) === 'modif_commande_id') {
                array_push($modif_commande_id, $value);
            }
            if (substr($key, 0, strlen('modif_article')) === 'modif_article') {
                array_push($modif_article, $value);
            }
            if (substr($key, 0, strlen('quantite_ajoute')) === 'quantite_ajoute') {
                array_push($modif_quantite, $value);
            }
            if (substr($key, 0, strlen('date_reception')) === 'date_reception') {
                array_push($modif_date_reception, $value);
            }

        }

        //on teste si il y a des valeurs à ajouter
        if(!empty($modif_quantite) && !empty($modif_article) && !empty($modif_commande_id)){
            //try {
                //ajoutReceptionCommandeAction($commande_id,$ligne_commande_id, $article_code, $quantite, $date_reception)
                $soap->call('ajoutReceptionCommande',array('commande_id'=>json_encode($modif_commande_id),
                    'ligne_commande_id'=>json_encode($modif_ligne_commande_id),
                    'article_code'=>json_encode($modif_article),'quantite'=>json_encode($modif_quantite),
                    'date_reception'=>json_encode($modif_date_reception)
                    ));
            //}
            //catch(\SoapFault $e){
            //    $erreur .=$e->getMessage();
            //}
        }

        //PARTIE COMMUNE//////////////////////////////////////////
        try {
            $return_menu = $soap->call('getMenu', array());
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }
        $menu_sous_menu = json_decode($return_menu);

        try {
            $return = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => '', 'email' => '',
                'telephone_portable' => '', 'reference_client' => '', 'notes' => ''));
            $liste_fournisseurs = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        try {
            $return_commandes = $soap->call('getCommandesFournisseurs', array('count' => 0, 'offset' => 0, 'fournisseur_id' => '',
                'fournisseur_nom' => '', 'commande_id' => '', 'article_code' => ''));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        $liste_commandes = json_decode($return_commandes);
        return $this->render('ImerirProduitBundle::ajoutReceptionCommande.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0,
            'commande_id'=>$modif_commande_id[0],'erreur'=>$erreur));
        ////////////////////////////////////////////////////////////

    }

    public function historiqueCommandeAction()
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
        $recherche_nom = $query->request->get('recherche_commande_fournisseur_nom_fournisseur');
        $recherche_article = $query->request->get('recherche_fournisseur_article');
        $recherche_commande = $query->request->get('recherche_commande_fournisseur_id');
        $recherche_date_deb = $query->request->get('recherche_date_deb');
        $recherche_date_fin = $query->request->get('recherche_date_fin');

        $erreur = '';

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
        if(!empty($recherche_date_deb))
            $date_deb = $recherche_date_deb;
        else
            $date_deb = '';
        if(!empty($recherche_date_fin))
            $date_fin = $recherche_date_fin;
        else
            $date_fin = '';
        //////////////////

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        try {
            $return = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => '', 'email' => '',
                'telephone_portable' => '', 'reference_client' => '', 'notes' => ''));
            $liste_fournisseurs = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        try {
            $return_commandes = $soap->call('getAllCommandesFournisseurs', array('count' => 0, 'offset' => 0, 'fournisseur_id' => '',
                'fournisseur_nom' => $nom, 'commande_id' => $commande, 'article_code' => $article,'date_deb'=>$date_deb,
                'date_fin'=>$date_fin));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        $liste_commandes = json_decode($return_commandes);
        return $this->render('ImerirFournisseurBundle::historiqueCommandeFournisseur.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0,'erreur'=>$erreur));
    }

}
