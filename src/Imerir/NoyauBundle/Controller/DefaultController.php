<?php

namespace Imerir\NoyauBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	
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
    	$mot_de_passe = $query->get('mot_de_passe');
    	
    	$client = new \SoapClient('http://localhost/serveur/web/app_dev.php/soap');
    	
	    try {
	    	$result = $client->__soapCall('login', array('username'=>$nom, 'passwd'=>$mot_de_passe));
		} catch (Exception $e) {
			echo '<script type="text/javascript">window.alert("'.$e.'");</script>';
		}
		$result_json = json_decode($result,true);
		$role[0] = $result_json['role'];
		
		$token = new UsernamePasswordToken($nom, $mot_de_passe,'main', $role);
		$context = $this->container->get('security.context');
		$context->setToken($token);
		
		
    	return $this->render('ImerirNoyauBundle:Default:index.html.twig',array('utilisateur' => $result_json['username'],'groupe' => $result_json['role']));
    }
}
