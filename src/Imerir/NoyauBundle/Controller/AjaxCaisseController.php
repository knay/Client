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
    	 
    	return new JsonResponse(array('prix' => $prix)); // Une réponse JSON
    }
}
