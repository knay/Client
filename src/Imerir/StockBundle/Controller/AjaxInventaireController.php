<?php

namespace Imerir\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controlleur permettant de gérer toutes les requetes AJAX servant 
 * pour l'inventaire.
 * 
 * ROUTES : /ajax/inventaireGetAttribut
 *          /ajax/inventaireGetArticleFromCodeBarre
 */
class AjaxInventaireController extends Controller
{
	/**
	 * Action permettant de récupérer tous les attributs d'un produit en
	 * partant du nom du produit.
	 * 
	 * @return \Symfony\Component\HttpFoundation\JsonResponse Une réponse JSON.
	 */
    public function getAttributsAction()
    {
    	$nom = $this->getRequest()->request->get('nom'); // On récup le nom passsé en POST
    	if (null === $nom)
    		$nom = '';
    	
    	$soap = $this->get('noyau_soap'); // Récup module soap
    	$attributs = $soap->call('getAttributFromNomProduit', array('nom'=>$nom));
    	
        return new JsonResponse($attributs); // Une réponse JSON
    }
    
    /**
     * Action permettant de récupérer un article à partir de son code barre.
     * @return \Symfony\Component\HttpFoundation\JsonResponse Une réponse JSON.
     */
    public function getArticleFromCodeBarreAction()
    {
    	$codeBarre = $this->getRequest()->request->get('codeBarre');
    	if (null === $codeBarre) // On récup le code barre passé en POST
    		$codeBarre = '';
    	
    	$soap = $this->get('noyau_soap'); // Récup module soap
    	$attributs = $soap->call('getArticleFromCodeBarre', array('codeBarre'=>$codeBarre));
    	 
    	return new JsonResponse($attributs); // Un réponse JSON
    }
}
