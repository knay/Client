<?php

namespace Imerir\VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RetourClientController extends Controller
{
    public function faireRetourClientAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$menu_sous_menu = array();
    	$facture = array();
    	$erreur = '';
    	$req = $this->get('request')->query;
    	
    	$dateDebut = $req->get('dateDebut');
    	$dateFin = $req->get('dateFin');
    	$ligneProduit = $req->get('nomLigneProduit');
    	$produit = $req->get('nomProduit');
    	$client = $req->get('critereClient');
    	 
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	try {
    		$return_facture = $soap->call('getFactureFromCritere', array('dateDebut' => $dateDebut,
    																	 'dateFin' => $dateFin,
    																	 'ligneProduit' => $ligneProduit,
    																	 'produit' => $produit,
    																     'client' => $client)); // On récupère les factures en fonction des critères
    																     
    		
    		$facture = json_decode($return_facture);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	// On récupère toutes les lignes produits
    	try {
    		$return_lp = $soap->call('getLigneProduit', array('count' => 0, 'offset' => 0, 'nom' => ''));
    		$lignesProduitsretour = json_decode($return_lp);
    	}
    	catch(\SoapFault $e) {
    		$erreur .=$e->getMessage();
    	}
    	
    	return $this->render('ImerirVenteBundle::retourClient.html.twig', array('ligne_produit' => $lignesProduitsretour,
    																			'result_menu' => $menu_sous_menu,
    																			'result_facture' => $facture,
    																	        'erreur' => $erreur));
    }
    
    public function faireRetourClientDetailsAction() {
    	$soap = $this->get('noyau_soap');
    	$query = $this->get('request')->request;
    	$erreur='';
    	$detail_facture = array();
    	$facture = array();
    	$numero = $query->get('id_facture');
    	
    	if($numero === null)
    		$numero = 0;
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	 
    	try {
    		$return_facture_detail = $soap->call('getDetailFromOneFacture', array('numero'=>$numero)); // On récupère detail de la facture
    		$detail_facture = json_decode($return_facture_detail);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	 
    	try {
    		$return_facture = $soap->call('getAllFacture', array()); // On récupère le menu/sous-menu
    		$facture = json_decode($return_facture);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	// On récupère toutes les lignes produits
    	try {
    		$return_lp = $soap->call('getLigneProduit', array('count' => 0, 'offset' => 0, 'nom' => ''));
    		$lignesProduitsretour = json_decode($return_lp);
    	}
    	catch(\SoapFault $e) {
    		$erreur .=$e->getMessage();
    	}
    	 
    	return $this->render('ImerirVenteBundle::retourClient.html.twig', array('ligne_produit' => $lignesProduitsretour, 
    																			'result_menu' => $menu_sous_menu,
    																			'result_facture' => $facture,
    																			'detail_facture'=> $detail_facture, 
    																			'erreur' => $erreur));
    }
    
    public function validerRetourClientAction() {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$menu_sous_menu = array();
    	$facture = array();
    	$erreur = '';
    	$req = $this->get('request')->request;
    	 
    	// Parametre de recherche
    	$dateDebut = $req->get('dateDebut');
    	$dateFin = $req->get('dateFin');
    	$ligneProduit = $req->get('nomLigneProduit');
    	$produit = $req->get('nomProduit');
    	$client = $req->get('critereClient');
    	
    	// Parametre d'enregistrement 
    	$codeBarre = $req->get('codeBarre');
    	$quantite = $req->get('quantite');
    	$promo = $req->get('promo');
    	$idFacture = $req->get('idFacture');
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	if ($codeBarre !== null && $quantite !== null && $promo !== null && $idFacture !== null) {
			try {
				$soap->call('enregistrerRetour', array('idFacture' => $idFacture, 
													   'quantite' => $quantite, 
												       'code_barre' => $codeBarre, 
											 		   'promo' => $promo));
			}
			catch(\SoapFault $e) {
				$erreur.=$e->getMessage();
			}
    	}
    	
    	try {
    		$return_facture = $soap->call('getFactureFromCritere', array('dateDebut' => $dateDebut,
    									 'dateFin' => $dateFin,
    									 'ligneProduit' => $ligneProduit,
    									 'produit' => $produit,
    									 'client' => $client)); // On récupère les factures en fonction des critères
    			
    	
    		$facture = json_decode($return_facture);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	// On récupère toutes les lignes produits
    	try {
    		$return_lp = $soap->call('getLigneProduit', array('count' => 0, 'offset' => 0, 'nom' => ''));
    		$lignesProduitsretour = json_decode($return_lp);
    	}
    	catch(\SoapFault $e) {
    		$erreur .=$e->getMessage();
    	}
    	 
    	return $this->render('ImerirVenteBundle::retourClient.html.twig', array('ligne_produit' => $lignesProduitsretour,
    																			'result_menu' => $menu_sous_menu,
    																			'result_facture' => $facture,
    																			'erreur' => $erreur));
    }
}
