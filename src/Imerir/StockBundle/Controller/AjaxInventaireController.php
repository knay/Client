<?php

namespace Imerir\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxInventaireController extends Controller
{
    public function getAttributsAction()
    {
    	$nom = $this->getRequest()->request->get('nom');
    	if (null === $nom)
    		$nom = '';
    	
    	$soap = $this->get('noyau_soap'); // Récup module soap
    	$attributs = $soap->call('getAttributFromNomProduit', array('nom'=>$nom));
    	
        return new JsonResponse($attributs);
    }
    
    public function getArticleFromCodeBarreAction()
    {
    	$codeBarre = $this->getRequest()->request->get('codeBarre');
    	if (null === $codeBarre) 
    		$codeBarre = '';
    	
    	$soap = $this->get('noyau_soap'); // Récup module soap
    	$attributs = $soap->call('getArticleFromCodeBarre', array('codeBarre'=>$codeBarre));
    	 
    	return new JsonResponse($attributs);
    }
}
