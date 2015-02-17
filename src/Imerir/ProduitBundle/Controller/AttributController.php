<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AttributController extends Controller
{
    public function modifAttributAction()
    {
    	//ini_set("soap.wsdl_cache_enabled", 0);
    	//todo mettre l'url dans un twig de ressources
    	
    	//$client = new \SoapClient('http://localhost/serveur/web/app_dev.php/soap');
        //permet de gérer le token par les cookies
        // $this->container->get('request')->getSession()->getId()
        //$client->__setCookie('PHPSESSID', 'totolitoto');
    	//$client->__setCookie('PHPSESSID', $this->getRequest()->cookies->get('PHPSESSID'));
    	//$result = $client->__soapCall('hello', array("name"=>'toto'));
		//$token = new UsernamePasswordToken($u->getUsername(), $u->getPassword(), 'main', $u->getRoles());
		
    	$soap = $this->get('noyau_soap');
    	$return = $soap->call('hello', array("name"=>'toto'));
    	
    	print_r($return);
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig', array('utilisateur' => 'jojo','groupe' => 'toto'));
    }
    
    public function saveAttributAction()
    {
    	return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
}
