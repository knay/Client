<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function ajoutProduitAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');
        $nomRech = $query->request->get('rechProduit');
        $ligneProduitRech = $query->request->get('rechLp');
        $attributRech = $query->request->get('rechAttribut');

        $erreur = '';

        if($nomRech === null)
            $nomRechVal = '';
        else
            $nomRechVal=$nomRech;
        if($ligneProduitRech === null)
            $lpRechVal = '';
        else
            $lpRechVal = $ligneProduitRech;
        if($attributRech === null)
            $attributRechVal = '';
        else
            $attributRechVal=$attributRech;

        //on récupère toutes les lignes produits
        try {
            $return_lp = $soap->call('getLigneProduit', array('count' => 0, 'offset' => 0, 'nom' => ''));
            $lignesProduitsretour = json_decode($return_lp);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on récupère tous les produits
        try {
            $return_produits = $soap->call('getProduit', array('count' => 0, 'offset' => 0, 'nom' => $nomRechVal, 'ligneproduit' => $lpRechVal, 'attribut' => $attributRechVal));
            $produitsRetour = json_decode($return_produits);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        try {
        	//on recupere le menu et sous menu
        	$return_menu = $soap->call('getMenu', array());
        	$menu_sous_menu = json_decode($return_menu);
        }
        catch(\SoapFault $e) {
        	$erreur.=$e->getMessage();
        }
        
        
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        return $this->render('ImerirProduitBundle::creerProduit.html.twig', array('ligne_produit' => $lignesProduitsretour,
        		'produits'=>$produitsRetour,
        		'result_menu' => $menu_sous_menu, 
        		'erreur'=>$erreur
        ));
    }

    public function execAjoutProduitAction()
    {

        $query = $this->get('request');
        $nom = $query->request->get('nomProduit');
        $ligneProduit = $query->request->get('nomLigneProduit');

        $erreur= '';
        $soap = $this->get('noyau_soap');
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        try {
            $return = $soap->call('ajoutProduit', array('nom' => $nom, 'ligneProduit' => $ligneProduit));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on récupère toutes les lignes produits
        try {
            $return_lp = $soap->call('getLigneProduit', array('count' => 0, 'offset' => 0, 'nom' => ''));
            $lignesProduitsretour = json_decode($return_lp);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on récupère tous les produits
        try {
            $return_produits = $soap->call('getProduit', array('count' => 0, 'offset' => 0, 'nom' => '', 'ligneproduit' => ''));
            $produitsRetour = json_decode($return_produits);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);
        
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        return $this->render('ImerirProduitBundle::creerProduit.html.twig', array('ligne_produit' => $lignesProduitsretour,
        		'produits'=>$produitsRetour,
        		'produit_add'=>$nom,
        		'lp_produit_add'=>$ligneProduit,
        		'result_menu' => $menu_sous_menu,
        		'erreur'=>$erreur
        ));


    }

    public function modifProduitAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');
        $nomRech = $query->request->get('rechProduit');
        $ligneProduitRech = $query->request->get('rechLp');
        $attributRech = $query->request->get('rechAttribut');

        $nom_modif_lp = $query->request->get('nom_lp');
        $id_modif_p = $query->request->get('id_p');
        $nom_modif_p = $query->request->get('nom_p');

        $erreur = '';

        if($nomRech === null)
            $nomRechVal = '';
        else
            $nomRechVal=$nomRech;
        if($ligneProduitRech === null)
            $lpRechVal = '';
        else
            $lpRechVal = $ligneProduitRech;
        if($attributRech === null)
            $attributRechVal = '';
        else
            $attributRechVal=$attributRech;

        //on récupère toutes les lignes produits
        try {
            $return_lp = $soap->call('getLigneProduit', array('count' => 0, 'offset' => 0, 'nom' => ''));
            $lignesProduitsretour = json_decode($return_lp);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on récupère tous les produits
        try {
            $return_produits = $soap->call('getProduit', array('count' => 0, 'offset' => 0, 'nom' => $nomRechVal, 'ligneproduit' => $lpRechVal, 'attribut' => $attributRechVal));
            $produitsRetour = json_decode($return_produits);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);
        
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        return $this->render('ImerirProduitBundle::creerProduit.html.twig',
            array('ligne_produit' => $lignesProduitsretour,'produits'=>$produitsRetour,
                'nom_modif_lp'=>$nom_modif_lp,'nom_modif_p'=>$nom_modif_p,'id_modif_p'=>$id_modif_p,'result_menu' => $menu_sous_menu,
                'erreur'=>$erreur));
    }

    public function execSupprProduitAction(){
    	$query = $this->get('request');
    	
    	$id_modif_p = $query->request->get('id_modif_p');
    	
    	$erreur = '';
    	
    	$soap = $this->get('noyau_soap');
    	
    	
    	if($id_modif_p !== null){
    		try{
    			$soap->call('supprProduit',array('id'=>$id_modif_p));
    		}
    		catch(\SoapFault $e) {
    			$erreur .=$e->getMessage();
    		}
    	}
    	
    	//on récupère toutes les lignes produits
    	try {
    		$return_lp = $soap->call('getLigneProduit', array('count' => 0, 'offset' => 0, 'nom' => ''));
    		$lignesProduitsretour = json_decode($return_lp);
    	}
    	catch(\SoapFault $e) {
    		$erreur .=$e->getMessage();
    	}
    	
    	//on récupère tous les produits
    	try {
    		$return_produits = $soap->call('getProduit', array('count' => 0, 'offset' => 0, 'nom' => '', 'ligneproduit' => ''));
    		$produitsRetour = json_decode($return_produits);
    	}
    	catch(\SoapFault $e) {
    		$erreur .=$e->getMessage();
    	}
    	
    	//on recupere le menu et sous menu
    	$return_menu = $soap->call('getMenu', array());
    	$menu_sous_menu = json_decode($return_menu);
    	
    	return $this->render('ImerirProduitBundle::creerProduit.html.twig', array('ligne_produit' => $lignesProduitsretour,
    			'produits'=>$produitsRetour,
    			'result_menu' => $menu_sous_menu,
    			'erreur'=>$erreur
    	));
    	
    }
    public function execModifProduitAction()
    {

        $query = $this->get('request');

        $nom_modif_p = $query->request->get('nom_modif_p');
        $old_nom_modif_p = $query->request->get('old_nom_modif_p');
        $nom_modif_lp = $query->request->get('nomLigneProduit');
        $id_modif_p = $query->request->get('id_modif_p');

        $erreur = '';

        $soap = $this->get('noyau_soap');
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        try {
            $return = $soap->call('modifProduit', array('nom_lp' => $nom_modif_lp, 'nom_p' => $nom_modif_p, 'id_p' => $id_modif_p));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on récupère toutes les lignes produits
        try {
            $return_lp = $soap->call('getLigneProduit', array('count' => 0, 'offset' => 0, 'nom' => ''));
            $lignesProduitsretour = json_decode($return_lp);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on récupère tous les produits
        try {
            $return_produits = $soap->call('getProduit', array('count' => 0, 'offset' => 0, 'nom' => '', 'ligneproduit' => ''));
            $produitsRetour = json_decode($return_produits);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);
        
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        return $this->render('ImerirProduitBundle::creerProduit.html.twig',
            array('ligne_produit' => $lignesProduitsretour,
                'produits'=>$produitsRetour,'old_p'=>$old_nom_modif_p,'new_p'=>$nom_modif_p,
                'new_lp'=>$nom_modif_lp,'result_menu' => $menu_sous_menu,'erreur'=>$erreur));


    }
}
