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
    	/*
    	try {
    		$return_moyenne = $soap->call('statsVenteMoyenneParMois', array('nbMois' => 1)); // On récupère le menu/sous-menu
    		$stats = json_decode($return_moyenne);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	*/
    	 
    	return $this->render('ImerirVenteBundle::facture.html.twig', array('result_menu' => $menu_sous_menu));
    }
}
