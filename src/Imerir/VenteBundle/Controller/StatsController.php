<?php

namespace Imerir\VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatsController extends Controller
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
    		$return_moyenne = $soap->call('statsVenteMoyenneParMois', array('nbMois' => 1)); // On récupère le menu/sous-menu
    		$stats = json_decode($return_moyenne);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	$axeX = array();
    	$axeY = array();
    	foreach ($stats as $valeur) {
    		array_push($axeX, $valeur->jour);
    		array_push($axeY, $valeur->montant);
    	}
    	 
    	return $this->render('ImerirVenteBundle::statsVenteMois.html.twig', array('result_menu' => $menu_sous_menu,
    			                                                         'axeX' => $axeX,
    			                                                         'axeY' => $axeY,
    		                	                                         'erreur' => $erreur));
    }
    
    public function topVenteAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$menu_sous_menu = array();
    	$erreur = '';
    	$stats = array();
    
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	 
    	try {
    		$return_moyenne = $soap->call('statsVenteTopVente', array('nbTop' => 3)); // On récupère le menu/sous-menu
    		$stats = json_decode($return_moyenne);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    
    	return $this->render('ImerirVenteBundle::statsTopVente.html.twig', array('result_menu' => $menu_sous_menu,
    																			 'stats' => $stats,
																    			 'erreur' => $erreur));
    }

}
