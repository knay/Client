<?php

namespace Imerir\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller permettant de connaitre les anniversaires de tous les 
 * clients. Par defaut, l'utilisateur à accès aux anniversaires du jours, 
 * mais il peut chercher tous les annniversaires de chaque mois.
 * 
 * ROUTES : contacts/anniversaires, contacts/anniversaires/rechercher
 */
class AnniversairesController extends Controller
{
	/**
	 * Permet de récupérer tous les anniversaires du mois.
	 * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
	 */
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$menu_sous_menu = array();
    	$anniversaire = array();
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
    		$return_tout_anniversaire = $soap->call('getAnniversaire', array('date'=>$mois)); // On récupère les anniversaires
    		$anniversaire = json_decode($return_tout_anniversaire);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	 
        return $this->render('ImerirContactBundle::anniversaires.html.twig', array('liste_des_anniversaires' => $anniversaire, 
        																		   'result_menu' => $menu_sous_menu, 
        																		   'erreur' => $erreur));
    }
    
    /**
     * Permet de récupérer tous les anniversaires du mois passé en paramètres en POST
     * au travers de la variable mois.
     * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
     */
    public function getAnniversaireAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$query = $this->get('request');
    
    	$menu_sous_menu = array();
    	$anniversaire_recherche = array();
    	
    	$mois = $query->query->get('mois');
    	if (null === $mois)
    		$mois = '';
    	$erreur = '';
    	 
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	 
    	try {
    		$return_anniversaire_recherche = $soap->call('getAnniversaire', array('date' => $mois)); // On récupère les anniversaires du mois
    		$anniversaire_recherche = json_decode($return_anniversaire_recherche);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    
    	return $this->render('ImerirContactBundle::anniversaires.html.twig', array('liste_des_anniversaires' => $anniversaire_recherche,
    																			   'result_menu' => $menu_sous_menu, 
    																			   'erreur' => $erreur));
    }
}

