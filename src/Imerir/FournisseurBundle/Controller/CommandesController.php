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
            $return_commandes = $soap->call('getCommandesFournisseurs', array('count' => 0, 'offset' => 0, 'fournisseur_id' => '',
                'fournisseur_nom' => $nom, 'commande_id' => $commande, 'article_code' => $article));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        $liste_commandes = json_decode($return_commandes);
        return $this->render('ImerirFournisseurBundle::ajoutCommandeFournisseur.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0,'erreur'=>$erreur));
    }


    public function execAjoutCommandeAction(){
        //PARTIE COMMUNE////////
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');
        ///////////////////////

        $commande_nom_fournisseur = array();
        $commande_article = array();
        $commande_qty = array();
        $commande_date = array();


        $erreur = '';

        foreach ($query->request as $key => $value) {
            if (substr($key, 0, strlen('fournisseur')) === 'fournisseur') {
                array_push($commande_nom_fournisseur, $value);
            }
            if (substr($key, 0, strlen('qty')) === 'qty') {
                array_push($commande_qty, $value);
            }
            if (substr($key, 0, strlen('article')) === 'article') {
                array_push($commande_article, $value);
            }
            if (substr($key, 0, strlen('date_commande')) === 'date_commande') {
                array_push($commande_date, $value);
            }
        }

        if(!empty($commande_nom_fournisseur) && !empty($commande_article) && !empty($commande_qty)){
            //$fournisseur_id, $article_code, $date_commande, $quantite_souhaite
            try {
                $soap->call('ajoutCommandeFournisseur', array('fournisseur_id' => json_encode($commande_nom_fournisseur),
                    'article_code' => json_encode($commande_article)
                , 'date_commande' => json_encode($commande_date), 'quantite_souhaite' => json_encode($commande_qty)));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }
        }

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
        return $this->render('ImerirFournisseurBundle::ajoutCommandeFournisseur.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0,'erreur'=>$erreur));
        ////////////////////////////////////////////////////////////

    }

    public function modifCommandeAction(){
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
        return $this->render('ImerirFournisseurBundle::ajoutCommandeFournisseur.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0,
            'liste_lignes_commandes'=>$liste_lignes_commandes,'modif_date_commande'=>$modif_date_commande,'erreur'=>$erreur));
        ////////////////////////////////////////////////////////////

    }


    public function execModifCommandeAction(){
        //PARTIE COMMUNE////////
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');
        ///////////////////////
        $modif_commande_est_visible = array();
        $modif_commande_id = array();
        $modif_ligne_commande_id = array();
        $modif_article = array();
        $modif_quantite_souhaite = array();

        $commande_nom_fournisseur = array();
        $commande_article = array();
        $commande_qty = array();
        $commande_date = array();

        $erreur = '';


        foreach ($query->request as $key => $value) {
            ////PARTIE MODIFICATION
            if (substr($key, 0, strlen('modif_est_visible')) === 'modif_est_visible') {
                array_push($modif_commande_est_visible, $value);
            }
            if (substr($key, 0, strlen('modif_commande_id')) === 'modif_commande_id') {
                array_push($modif_commande_id, $value);
            }
            if (substr($key, 0, strlen('modif_ligne_commande_id')) === 'modif_ligne_commande_id') {
                array_push($modif_ligne_commande_id, $value);
            }
            if (substr($key, 0, strlen('modif_article')) === 'modif_article') {
                array_push($modif_article, $value);
            }
            if (substr($key, 0, strlen('modif_quantite_souhaite')) === 'modif_quantite_souhaite') {
                array_push($modif_quantite_souhaite, $value);
            }
            ////PARTIE INSERTION DE DONNEES
            if (substr($key, 0, strlen('fournisseur')) === 'fournisseur') {
                array_push($commande_nom_fournisseur, $value);
            }
            if (substr($key, 0, strlen('qty')) === 'qty') {
                array_push($commande_qty, $value);
            }
            if (substr($key, 0, strlen('article')) === 'article') {
                array_push($commande_article, $value);
            }
            if (substr($key, 0, strlen('date_commande')) === 'date_commande') {
                array_push($commande_date, $value);
            }
        }

        //on teste si il y a des valeurs à ajouter
        if(empty($commande_article[0]) && empty($commande_qty[0])){
            //MODIFICATION
            try {
                $soap->call('modifCommandeFournisseur', array('fournisseur_id' => json_encode($commande_nom_fournisseur)
                , 'commande_id' => json_encode($modif_commande_id),
                    'ligne_commande_id' => json_encode($modif_ligne_commande_id),
                    'date_commande' => json_encode($commande_date), 'article_code' => json_encode($modif_article),
                    'quantite_souhaite' => json_encode($modif_quantite_souhaite),
                    'est_visible' => json_encode($modif_commande_est_visible)));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }

        }
        else{
            //MODIFICATION
            /*
             * modifCommandeFournisseurAction($commande_id, $ligne_commande_id, $article_code, $date_commande,
												   $quantite_souhaite, $est_visible)
             */
            try {
                $soap->call('modifCommandeFournisseur', array('fournisseur_id' => json_encode($commande_nom_fournisseur)
                , 'commande_id' => json_encode($modif_commande_id),
                    'ligne_commande_id' => json_encode($modif_ligne_commande_id),
                    'date_commande' => json_encode($commande_date), 'article_code' => json_encode($modif_article),
                    'quantite_souhaite' => json_encode($modif_quantite_souhaite),
                    'est_visible' => json_encode($modif_commande_est_visible)));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }
            //INSERTION
            try {
                $soap->call('ajoutCommandeFournisseur', array('fournisseur_id' => json_encode($commande_nom_fournisseur),
                    'article_code' => json_encode($commande_article)
                , 'date_commande' => json_encode($commande_date), 'quantite_souhaite' => json_encode($commande_qty)));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }
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
        return $this->render('ImerirFournisseurBundle::ajoutCommandeFournisseur.html.twig',array('result_menu' => $menu_sous_menu,
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
            $return_commandes = $soap->call('getCommandesFournisseurs', array('count' => 0, 'offset' => 0, 'fournisseur_id' => '',
                'fournisseur_nom' => $nom, 'commande_id' => $commande, 'article_code' => $article));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        $liste_commandes = json_decode($return_commandes);
        return $this->render('ImerirFournisseurBundle::historiqueCommandeFournisseur.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0,'erreur'=>$erreur));
    }

}
