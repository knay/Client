<?php

namespace Imerir\ContactBundle\Controller;

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
    		$return = $soap->call('getNombreContactsSmsMail',array()); // On récupère le menu/sous-menu
    		$stats = json_decode($return);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}

    	 
    	return $this->render('ImerirContactBundle::statsClientsSmsMail.html.twig', array('result_menu' => $menu_sous_menu,
    			                                                         'stats' => $stats,
    		                	                                         'erreur' => $erreur));
    }
    
    public function contactAgesAction()
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
    		$return = $soap->call('getNombreContactsParTrancheAge', array()); // On récupère le menu/sous-menu
    		$stats = json_decode($return);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    
    	return $this->render('ImerirContactBundle::statsClientsAge.html.twig', array('result_menu' => $menu_sous_menu,
    																			 'stats' => $stats,
																    			 'erreur' => $erreur));
    }

	public function contactVillesAction()
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
			$return = $soap->call('getNombreContactsParVille', array()); // On récupère le menu/sous-menu
			$stats = json_decode($return);
		}
		catch(\SoapFault $e) {
			$erreur.=$e->getMessage();
		}

		return $this->render('ImerirContactBundle::statsClientsVille.html.twig', array('result_menu' => $menu_sous_menu,
			'stats' => $stats,
			'erreur' => $erreur));
	}

}
