<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AttributController extends Controller
{
    public function modifAttributAction()
    {
    	$soap = $this->get('noyau_soap');
    	
    	$args = array(
    			'count' => 0,
    			'offset' => 0,
    			'nom' => ''
    	);
    	
    	$return = $soap->call('getLigneProduit', $args);
    	$jsonLigneProduit = json_decode($return);
    	
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig', array('ligne_produit' => $jsonLigneProduit));
    }
    
    public function saveAttributAction()
    {
    	return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
}
