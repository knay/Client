<?php

namespace Imerir\NoyauBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Le controlleur permettant la gestion de la caisse.
 * ROUTE : /
 */
class CaisseController extends Controller
{
	/**
	 * L'action appelée lorsque l'on demande la page d'accueil qui correspond à la caisse.
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function caisseAction()
    {
    	$soap = $this->get('noyau_soap');
    	$erreur = '';
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // Récup du menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	return $this->render('ImerirNoyauBundle:Default:index.html.twig', array('result_menu' => $menu_sous_menu, 
    			                                                                'erreur' => $erreur));
    }
    
    /**
     * L'action est appelée lorsque l'on valide l'achat.
     * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
     */
    public function saveCaisseAction() 
    {
    	$soap = $this->get('noyau_soap');
    	$erreur = '';
    	
    	$req = $this->getRequest()->request;
    	$tabArticle = array();
    	
    	// On va parser la request pour former un tableau avec les articles de l'inventaire propre
    	foreach ($req as $key => $value) {
    		$clef = explode('_', $key);
    		
    		if ($clef[0] === 'code') { // Si c'est le code barre
    			$tabArticle[intval($clef[1])]['codeBarre'] = $value;
    		}
    		else if ($clef[0] === 'quantite') { // Si c'est la quantite
    			$tabArticle[intval($clef[1])]['quantite'] = intval($value);
    		}
    		else if ($clef[0] === 'promo') { // Si c'est la promo
    			$tabArticle[intval($clef[1])]['promo'] = intval($value);
    		}
    	}
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // Récup du menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	if(count($tabArticle) !== 0) {
	    	try {
	    		$return_menu = $soap->call('enregistrerAchat', array('articles' => json_encode($tabArticle))); // On enregistre l'achat au travers de SOAP
	    	}
	    	catch(\SoapFault $e) {
	    		$erreur.=$e->getMessage();
	    	} 
    	}
    	
    	return $this->render('ImerirNoyauBundle:Default:index.html.twig', array('result_menu' => $menu_sous_menu, 
    			                                                                'erreur' => $erreur));
    }
}
