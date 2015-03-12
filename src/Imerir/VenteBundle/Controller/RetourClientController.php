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
    	$req = $this->get('request')->request;
    	
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
    		/*$return_facture = $soap->call('getAllFacture', array('dateDebut' => $dateDebut,
    																	 'dateFin' => $dateFin,
    																	 'ligneProduit' => $ligneProduit,
    																	 'produit' => $produit,
    																     'client' => $client)); // On récupère les factures en fonction des critères
    																     */
    		
    		$return_facture = $soap->call('getAllFacture', array('date'=>$dateDebut, 'client'=>$client)); // On récupère le menu/sous-menu
    		$facture = json_decode($return_facture);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	return $this->render('ImerirVenteBundle::retourClient.html.twig', array('result_menu' => $menu_sous_menu,
    																			'result_facture'=>$facture,
    																	        'erreur'=>$erreur));
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
    	 
    	return $this->render('ImerirVenteBundle::retourClient.html.twig', array('result_menu' => $menu_sous_menu,
    																			'result_facture' => $facture,
    																			'detail_facture'=>$detail_facture, 
    																			'erreur' => $erreur));
    }
}