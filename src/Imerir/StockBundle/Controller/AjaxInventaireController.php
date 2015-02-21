<?php

namespace Imerir\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class AjaxInventaireController extends Controller
{
    public function getAttributsAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup module soap
    	$attributs = $soap->call('getAttributFromNomProduit', array('nom'=>$this->getRequest()->request->get('nom')));
    	
        return new JsonResponse($attributs);
    }
}
