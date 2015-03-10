<?php

namespace Imerir\NoyauBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Le controlleur permettant la gestion de la caisse.
 * ROUTE : /ajax/getPrix
 */
class AjaxCaisseController extends Controller
{
	/**
	 * L'action qui permet de récupérer le prix depuis le serveur SOAP en AJAX.
	 * @return \Symfony\Component\HttpFoundation\Response La réponse JSON.
	 */
    public function getPrixFromCodeBarreAction()
    {
    	$codeBarre = $this->getRequest()->request->get('codeBarre'); // On récup le nom passsé en POST
    	if (null === $codeBarre)
    		$codeBarre = '';
    	
    	try {
    		$soap = $this->get('noyau_soap'); // Récup module soap
    		$prix = $soap->call('getPrixFromCodeBarre', array('codeBarre' => $codeBarre));
    	} catch(\SoapFault $e) {
    		return new JsonResponse(array('erreur' => $e->getMessage())); // Une réponse JSON
    	}
    	 
    	return new JsonResponse(json_decode($prix)); // Une réponse JSON
    }
    
    /**
     * L'action qui permet de rechercher des clients depuis le serveur SOAP en AJAX.
     * @return \Symfony\Component\HttpFoundation\Response La réponse JSON.
     */
    public function rechercheClientAction() {
    	$reponse = array();
    	
    	$critere = $this->getRequest()->request->get('critere'); // On récup les criteres de recherche passsés en POST
    	if (null === $critere)
    		$critere = '';
    	
    	try {
    		$soap = $this->get('noyau_soap'); // Récup module soap
    		$reponse = $soap->call('getContactFromEverything', array('critere' => $critere));
    	} catch(\SoapFault $e) {
    		return new JsonResponse(array('erreur' => $e->getMessage())); // Une réponse JSON
    	}
    	
    	return new JsonResponse($reponse); // Une réponse JSON
    }
}
