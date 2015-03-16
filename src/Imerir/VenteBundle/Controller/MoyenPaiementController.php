<?php

namespace Imerir\VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MoyenPaiementController extends Controller
{
    public function indexAction()
    {
        $soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$query = $this->get('request');
    	
    	//on recuperer les variables en post ici
    	$menu_sous_menu = array();
    	$paiement = array();
    	$nom = $query->request->get('nom');
    	$erreur = '';
    	 
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	//check si l'utilisateur est en insertion ou juste pour ouvrir la page
    	if($nom != ''){
    		try {
    			$soap->call('insererModePaiement', array('nom'=>$nom)); 
    		}
    		catch(\SoapFault $e) {
    			$erreur.=$e->getMessage();
    		}
    	}
    	
    	try {
    		$return_mode_paiement = $soap->call('getAllModePaiement', array()); 
    		$paiement = json_decode($return_mode_paiement);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	return $this->render('ImerirVenteBundle::moyenPaiement.html.twig', array('result_menu' => $menu_sous_menu, 
    																			 'liste_mode_paiement' => $paiement, 
    																			 'erreur' => $erreur));
     }

     public function afficherEnModificationMoyenPaiementAction(){
     	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
     	$query = $this->get('request');
     	 
     	//on recuperer les variables en post ici
     	$menu_sous_menu = array();
     	$paiement = array();
     	$selected_id = $query->request->get('id_selected');
     	$selected_nom = $query->request->get('nom_selected');
     	$erreur = '';
     	
     	try {
     		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
     		$menu_sous_menu = json_decode($return_menu);
     	}
     	catch(\SoapFault $e) {
     		$erreur.=$e->getMessage();
     	}
     	
     	try {
     		$return_mode_paiement = $soap->call('getAllModePaiement', array());
     		$paiement = json_decode($return_mode_paiement);
     	}
     	catch(\SoapFault $e) {
     		$erreur.=$e->getMessage();
     	}
     	 
     	return $this->render('ImerirVenteBundle::moyenPaiement.html.twig', array('result_menu' => $menu_sous_menu,
     																			 'liste_mode_paiement' => $paiement,
     																			 'id_selectioner' => $selected_id,
     																			 'nom_selectioner' => $selected_nom,
     																			 'erreur'=>$erreur)); 
     }
     
     public function modificationMoyenPaiementAction(){
     	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
     	$query = $this->get('request');
     	 
     	//on recuperer les variables en post ici
     	$modifier_nom_par = $query->request->get('nom_modifier');
     	$modifier_nom_dont_id = $query->request->get('id_modifier');
     	 
     	if($modifier_nom_par != ''){
          	try {
     	    	$soap->call('modifierModePaiement', array('id'=>$modifier_nom_dont_id,'nom'=>$modifier_nom_par));
    		}
    	    catch(\SoapFault $e) {
     	    	$erreur.=$e->getMessage(); // TODO gérer cette erreur soap... pour l'afficher 
     		}
     	}
     	return $this->redirect($this->generateUrl('imerir_vente_creation_moyen_paiement'));
     }
     
     public function supprimerMoyenPaiementAction(){
     	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
     	$query = $this->get('request');
     	
     	$modifier_nom_dont_id = $query->request->get('id_modifier');
     	
     	try {
     		$soap->call('supprimerModePaiement', array('id'=>$modifier_nom_dont_id));
     	}
     	catch(\SoapFault $e) {
     		$erreur.=$e->getMessage();  // TODO gérer cette erreur soap... pour l'afficher 
     	}
     	return $this->redirect($this->generateUrl('imerir_vente_creation_moyen_paiement'));
     }
}


