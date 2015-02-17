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
    	
    	$client = new \SoapClient('http://localhost/serveur/web/app_dev.php/soap');
    	//$client->__setCookie('PHPSESSID', $this->getRequest()->cookies->get('PHPSESSID'));
    	$result = $client->__soapCall('login', array('username'=>'alba', 'passwd'=>'alba'));
		//$token = new UsernamePasswordToken($u->getUsername(), $u->getPassword(), 'main', $u->getRoles());
    	//print_r($result);
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig', array('utilisateur' => 'jojo','groupe' => 'toto'));
    }
    
    public function saveAttributAction()
    {
    	return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
}
