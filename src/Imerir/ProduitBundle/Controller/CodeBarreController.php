<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Controlleur permettant de gérer la génération de code barre
 */
class CodeBarreController extends Controller
{
	/**
	 * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
	 */
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$req = $this->getRequest()->request;
    	$erreur = ''; // En cas d'erreur
    	$menu_sous_menu = array();
    	$codeBarre = array();
    	$lstCodeBarre = array();
    	$nbX = 2;
    	$nbY = 5;
    	
    	if ($req->get('nb_etiquette_Y') !== null) {
    		$nbY = (int)$req->get('nb_etiquette_Y');
    		if ($nbY > 10) 
    			$nbY = 10;
    		else if ($nbY < 0)
    			$nbY = 1;
    	}
    	if ($req->get('nb_etiquette_X') !== null) {
    		$nbX = (int)$req->get('nb_etiquette_X');
    		if ($nbX > 4)
    			$nbX = 4;
    		else if ($nbX < 0)
    			$nbX = 1;
    	}
    	
    	if ($req->get('num_code_barre') === null) {
	    	try {
	    		// On récupère tous les produits pour les afficher dans un <select>
	    		$return_all_code = $soap->call('getAllCodeBarre', array());
	    		$allCodeBarre = json_decode($return_all_code);
	    	}
	    	catch(\SoapFault $e) {
	    		$erreur.=$e->getMessage();
	    	}
	    	for ($j = 0 ; $j < $nbX*$nbY ; $j++) {
		    	$codeBarre = '';
		    	for ($i = 0 ; $i < 13 ; $i++) {
		    		$codeBarre.= rand(0, 9);
		    	}
		    	while (in_array($codeBarre, $allCodeBarre)) {
		    		$codeBarre = '';
		    		for ($i = 0 ; $i < 13 ; $i++) {
		    			$codeBarre.= rand(0, 9);
		    		}
		    	}
		    	array_push($lstCodeBarre, $codeBarre);
	    	}
    	}
    	else {
    		$codeBarre = $req->get('num_code_barre');
    	}
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
   		}
   		
        return $this->render('ImerirProduitBundle::codeBarre.html.twig', array('code_barre' => $lstCodeBarre, 
        		                                                               'result_menu' => $menu_sous_menu,
        		                                                               'nbX' => $nbX,
        		                                                               'nbY' => $nbY,
        		                                                               'erreur' => $erreur));
    }
}
