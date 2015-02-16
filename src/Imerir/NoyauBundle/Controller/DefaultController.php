<?php

namespace Imerir\NoyauBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
//     	ini_set("soap.wsdl_cache_enabled", 0);
//     	//todo mettre l'url dans un twig de ressources
//     	$client = new \SoapClient('http://192.168.1.60/serveur/web/app_dev.php?wsdl');
//     	$result = $client->__soapCall('hello', array('name'=>'DUCON'));
    	
        return $this->render('ImerirNoyauBundle:Default:index.html.twig');
    }
    
    public function authentificationAction()
    {
    	return $this->render('ImerirNoyauBundle:Default:authentification.html.twig');
    }
    
    public function testAction()
    {
    	return $this->render('ImerirNoyauBundle:Default:test.html.twig');
    }
    
    public function getindexAction()
    {
    	$query = $this->getRequest();
    	$nom = $query->get('utilisateur');
    	$prenom = $query->get('mot_de_passe');
    	return $this->render('ImerirNoyauBundle:Default:index.html.twig',array('utilisateur' => $nom,'mot_de_passe' => $prenom));
    }
}
