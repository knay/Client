<?php

namespace Imerir\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnniversairesController extends Controller
{
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$menu_sous_menu = array();
    	$mois= '';
    	$erreur = '';
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	try {
    		$return_tout_anniversaire = $soap->call('getAnniversaire', array('date'=>$mois)); // On récupère le menu/sous-menu
    		$anniversaire = json_decode($return_tout_anniversaire);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	 
        return $this->render('ImerirContactBundle::anniversaires.html.twig', array('liste_des_anniversaires'=>$anniversaire,'result_menu' => $menu_sous_menu));
    }
    
    public function getAnniversaireAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$query = $this->get('request');
    
    	$menu_sous_menu = array();
    	$mois = $query->request->get('mois');
    	$erreur = '';
    	 
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	 
    	try {
    		$return_anniversaire_recherche = $soap->call('getAnniversaire', array('date'=>$mois)); // On récupère le menu/sous-menu
    		$anniversaire_recherche = json_decode($return_anniversaire_recherche);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    
    	return $this->render('ImerirContactBundle::anniversaires.html.twig', array('liste_des_anniversaires'=>$anniversaire_recherche,'result_menu' => $menu_sous_menu,'erreur'=>$erreur));
    }
}

