<?php

namespace Imerir\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/** 
 * Controlleur permettant de gérer la quantite de stock
 */
class StockController extends Controller
{
	
	/**
	 * Action appel� lorsqu'on est sur la page des stock
	 * 
	 */
    public function getStockAction()
    {
    	$soap = $this->get('noyau_soap');
    	$req = $this->getRequest();
    	$erreur = '';
    	
    	$ligneProduit = $req->get('nomLigneProduit');
    	$produit = $req->get('nomProduit');
    	$article = $req->get('nomArticle');
    	
    	try {
    		$return_stock = $soap->call('getStock', array('LigneProduit'=>$ligneProduit,'Produit'=>$produit,'Article'=>$article)); //on recupere le stock enfocntion des parametres
    		$stock = json_decode($return_stock);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	} 
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récup le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
   		}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	try {
    		$return_all_ligne_produit = $soap->call('getAllLigneProduit',array());//on recupere toutes les lignes produits
    		$all_ligne_produit = json_decode($return_all_ligne_produit);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
        return $this->render('ImerirStockBundle::stock.html.twig',array('result_stock' => $stock,
        		'result_menu' => $menu_sous_menu,
        		'result_all_ligne_produit'=>$all_ligne_produit,
        		'erreur' => $erreur
        ));
    }
    
    /**
     * 
     */
    public function getProduitFromLigneProduitAction(){
    	$ligne_produit = $this->getRequest()->request->get('nomLigneProduit'); // On récup le nom passsé en POST
    	if (null === $ligne_produit)
    		$ligne_produit = '';
    	
    	try {
    		$soap = $this->get('noyau_soap'); // Récup module soap
    		$produits = $soap->call('getProduitFromLigneProduit', array('LigneProduit' => $ligne_produit));
    	}
    	catch(\SoapFault $e) {
    		return new JsonResponse(array('erreur' => $e->getMessage()));
    	}
    	
    	return new JsonResponse($produits); // Une réponse JSON
    }
    
}
