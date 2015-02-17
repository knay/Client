<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AttributController extends Controller
{
    public function modifAttributAction()
    {
    	$soap = $this->get('noyau_soap');
    	$return = $soap->call('hello', array("name"=>'toto'));
    	
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig', array('utilisateur' => 'jojo','groupe' => 'toto'));
    }
    
    public function saveAttributAction()
    {
    	return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
}
