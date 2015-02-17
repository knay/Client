<?php 
namespace Imerir\NoyauBundle\Services;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SoapService
{
	private $client;
	private $container;
	
    public function __construct($container) {
    	$this->container = $container;
    	
    	$url = $this->container->getParameter('soap_serveur'); // Récup de l'url du serveur dans la conf
    	$this->client = new \SoapClient($url);
    }
    
    public function call($methode, $args) {
    	$session = $this->container->get('request')->getSession();
    	$token = $session->get('token');
    	
    	if (isset($token))
    		$this->client->__setCookie('PHPSESSID', $token);
    	
    	return $this->client->__soapCall($methode, $args);
    }
    
    public function login($usr, $passwd) {
    	$this->client->__setCookie('PHPSESSID', uniqid());
    	 
    	try {
    		$result = $this->client->__soapCall('login', array('username' => $usr, 'passwd' => $passwd));
    		$result_json = json_decode($result, true);
    	} catch (Exception $e) {
    		
    	}
    	
    	$result_json = json_decode($result, true);
    	$token = $result_json['token'];
    	$this->container->get('request')->getSession()->set('token', $token);
    	
    	$role = array();
    	$role[0] = $result_json['role'];
    	$token = new UsernamePasswordToken($usr, $passwd,'main', $role);
    	$context = $this->container->get('security.context');
    	$context->setToken($token);
    }
}