<?php

namespace Imerir\NoyauBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	ini_set("soap.wsdl_cache_enabled", 0);
    	//todo mettre l'url dans un twig de ressources
    	$client = new \SoapClient('http://localhost/alba/web/app_dev.php?wsdl');
    	$result = $client->__soapCall('hello', array('name'=>'DUCON'));
    	
        return $this->render('ImerirNoyauBundle:Default:index.html.twig', array('soap_return'=>$result));
    }
    
    public function authentificationAction()
    {
    	return $this->render('ImerirNoyauBundle:Default:authentification.html.twig');
    }
    
    public function getAuthentification()
    {
    }
    
}
