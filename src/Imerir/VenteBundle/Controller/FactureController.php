<?php

namespace Imerir\VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FactureController extends Controller
{
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$query = $this->get('request');
    	
    	//on recuperer les variables en post ici
    	$date = $query->request->get('date');
    	$client = $query->request->get('client');
    	$menu_sous_menu = array();
    	
    	$erreur = '';
    	 
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	//gestion des cas possible de retour en post a traiter avant la requete
    	if($date == 'aaaa-mm-jj'){
    		$date = '';
    	}
    	if($client == 'Nom'){
    		$client = '';
    	}
    	
    	try {
    		$return_facture = $soap->call('getAllFacture', array('date'=>$date,'client'=>$client)); // On récupère le menu/sous-menu
    		$facture = json_decode($return_facture);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	return $this->render('ImerirVenteBundle::facture.html.twig', array('result_menu' => $menu_sous_menu,'result_facture'=>$facture));
    }
    
    /**
     * Fonction ajax permet de retourner les details d'une facture
     */
    public function factureDetailFromLigneFactureAction(){
    	$soap = $this->get('noyau_soap');
        $query = $this->get('request');
        $erreur='';
        //on recuperer les variables en post ici variable 
        $numero = $query->request->get('ligne_numero');
//         $date = $query->request->get('ligne_date');
//         $client = $query->request->get('ligne_client');
//         $montant = $query->request->get('ligne_montant');
        
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	try {
    		$return_facture_detail = $soap->call('getDetailFromOneFacture', array('numero'=>$numero)); // On récupère le menu/sous-menu
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
    	
    	return $this->render('ImerirVenteBundle::facture.html.twig', array('result_menu' => $menu_sous_menu,'result_facture'=>$facture,'erreur'=>$erreur,'detail_facture_test'=>$detail_facture));
    }
    
    
}
