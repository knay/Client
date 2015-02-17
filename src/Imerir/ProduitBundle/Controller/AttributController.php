<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AttributController extends Controller
{
    public function modifAttributAction()
    {
    	ini_set("soap.wsdl_cache_enabled", 0);
    	//todo mettre l'url dans un twig de ressources
    	$client = new \SoapClient('http://localhost/alba/web/app_dev.php/soap');
        //permet de gérer le token par les cookies
        $client->__setCookie('PHPSESSID',uniqid());
    	$result = $client->__soapCall('login', array('username'=>'alba', 'passwd'=>'alba'));
    	print_r($result);
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
    
    public function saveAttributAction()
    {
    	return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }
}
