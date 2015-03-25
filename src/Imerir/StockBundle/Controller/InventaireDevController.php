<?php

namespace Imerir\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/** 
 * Controlleur permettant de réagir à toutes les demande concernant l'inventaire (à 
 * l'exception des requêtes AJAX).
 * 
 * ROUTES : /inventaire
 *          /inventaire/save
 */
class InventaireDevController extends Controller
{
	/**
	 * Action permettant de réagir lorsque l'utilisateur accède à la page d'inventaire.
	 * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
	 */
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap');
    	$erreur = '';
    	
    	try {
    		$return_all_ligne_produit = $soap->call('getAllLigneProduit',array());//on recupere toutes les lignes produits
    		$all_ligne_produit = json_decode($return_all_ligne_produit);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	

    	// S'il y a des produits, on récupère également les attributs du premier 
    	// (parce que c'est celui qui est selectionné au départ)
    	if (isset($produitsRetour[0]->p)) {
    		try {  
    			$retSoapAttributs = $soap->call('getAttributFromNomProduit', array('nom'=>$produitsRetour[0]->p));
    		}
    		catch(\SoapFault $e) {
    			$erreur.=$e->getMessage();
    		}	
    	}
    	else
    		$retSoapAttributs = '';
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récup le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
   		}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	} 
    	
        return $this->render('ImerirStockBundle::inventaireDev.html.twig', 
        	                  array('result_all_ligne_produit'     => $all_ligne_produit,
        			                'attributs'   => json_decode($retSoapAttributs),
        	   		                'result_menu' => $menu_sous_menu,
        	                  		'erreur'      => $erreur));
    }
    
    /**
     * Action appéelée lorsque l'utilisateur enregistre son inventaire.
     * @return \Symfony\Component\HttpFoundation\Response La réponse HTML (la même que pour l'index action).
     */
    public function saveAction() {
    	$soap = $this->get('noyau_soap');
    	$req = $this->getRequest()->request;
    	$tabArticle = array();
    	$erreur = '';
    	
    	try {
    		$return_all_ligne_produit = $soap->call('getAllLigneProduit',array());//on recupere toutes les lignes produits
    		$all_ligne_produit = json_decode($return_all_ligne_produit);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	// S'il y a des produits, on récupère également les attributs du premier
    	// (parce que c'est celui qui est selectionné au départ)
    	if (isset($produitsRetour[0]->p)) {
    		try {
	    		$retSoapAttributs = $soap->call('getAttributFromNomProduit', array('nom'=>$produitsRetour[0]->p));
    		}
    		catch(\SoapFault $e) {
	    		$erreur.=$e->getMessage();
    		}
    	}
    	else 
    		$retSoapAttributs = '';
    	
    	// On va parser la request pour former un tableau avec les articles de l'inventaire propre
    	foreach ($req as $key => $value) {
    		$clef = explode('_', $key);
    		
    		if (isset($clef[1])) {
    			//$tabArticle[intval($clef[1])]['attributs'] = array();
	    		if ($clef[0] === 'produit') { // Si c'est le nom du produit concerné
	    			$tabArticle[intval($clef[1])]['produit'] = $value;
	    		}
	    		else if ($clef[0] === 'code') { // Si c'est le code barre
	    			$tabArticle[intval($clef[1])]['codeBarre'] = $value;
	    		}
	    		else if ($clef[0] === 'quantite') { // Si c'est la quantite
	    			$tabArticle[intval($clef[1])]['quantite'] = intval($value);
	    		}
	    		else if ($clef[0] === 'caract') { // Si c'est une valeur d'attribut
	    			$attributs = explode('_', $value);
	    			$tabArticle[intval($clef[1])]['attributs'][$attributs[0]] = $attributs[1];
	    		}
	    		else if ($clef[0] === 'prixClient') { // Si c'est un prix client
	    			$tabArticle[intval($clef[1])]['prixClient'] = floatval($value);
	    		}
	    		else if ($clef[0] === 'prixFournisseur') { // Si c'est un prix fournisseur
	    			$tabArticle[intval($clef[1])]['prixFournisseur'] = floatval($value);
	    		}
    		}
    	}
    	 
    	try {
    		$soap->call('faireInventaire', array('articles'=> json_encode($tabArticle), 'avecPrix'=>true)); // On enregistre toutes les données de l'inventaire
    	}
    	catch(\SoapFault $e) {
    		$erreur .= $e->getMessage();
    	}
    	
    	try {
	    	$return_menu = $soap->call('getMenu', array()); // On récup le menu/sous-menu
	    	$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur .= $e->getMessage();
    	}
    	
    	return $this->render('ImerirStockBundle::inventaireDev.html.twig',
			    			  array('result_all_ligne_produit'     => $all_ligne_produit,
			    			 	    'attributs'   => json_decode($retSoapAttributs),
			    			 	    'result_menu' => $menu_sous_menu,
			    			  		'erreur'      => $erreur));
    }
}
