<?php

namespace Imerir\VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FactureController extends Controller
{
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$menu_sous_menu = array();
    	$erreur = '';
    	 
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
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
    	
    	return $this->render('ImerirVenteBundle::facture.html.twig', array('result_menu' => $menu_sous_menu,'result_facture'=>$facture));
    }
    
    public function factureDetailFromLigneFacture(){
    	$soap = $this->get('noyau_soap');
        $query = $this->get('request');
        
        //on recuperer les variables en post ici variable 
        $id_tableau = $query->request->get('id_select');
        $ref_tableau = $query->request->get('ref_select');

        $result = array("id"=>$id_tableau,"ref"=>$ref_tableau);
        
        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);
        
        return $this->render('ImerirVenteBundle::facture.html.twig',
            array('result_menu' => $menu_sous_menu,'resultat'=>$result));
   
    }
}
