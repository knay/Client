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
<<<<<<< HEAD
        //permet de gérer le token par les cookies
        $client->__setCookie('PHPSESSID',uniqid());
=======
    	//$client->__setCookie('PHPSESSID', $this->getRequest()->cookies->get('PHPSESSID'));
>>>>>>> f2fad40849268f5dc92950a0fdb0f4025008393f
    	$result = $client->__soapCall('login', array('username'=>'alba', 'passwd'=>'alba'));
//    	$token = new UsernamePasswordToken($u->getUsername(), $u->getPassword(), 'main', $u->getRoles());
    	
    	print_r($result);
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
    
    public function saveAttributAction()
    {
    	return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
}
