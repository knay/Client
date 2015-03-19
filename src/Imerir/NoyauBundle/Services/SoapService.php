<?php 
namespace Imerir\NoyauBundle\Services;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Service permettant de faire des appels SOAP.
 * Ce service s'occupe de la gestion du token de connexion au serveur SOAP.
 * Pour se connecter au serveur soap faire appel à la fonction login().
 */
class SoapService
{
	private $client; // Le client SOAP
	private $container; // Le container symfony pour récupérer l'url du serveur dans la config
	
	
	/**
	 * Constructeur de la classe.
	 * Initialise la connexion avec le serveur SOAP.
	 * 
	 * TODO gérer les entrée utilisateur
	 * TODO gérer cas d'erreur
	 * @param unknown $container Container symfony pour récupérer l'url du serveur.
	 */
    public function __construct($container) {
    	$this->container = $container;
    	
		ini_set("soap.wsdl_cache_enabled", 0); // On desactive le cache, pour pas avoir de problèmes
		
    	$url = $this->container->getParameter('soap_serveur'); // Récup de l'url du serveur dans la conf
    	$this->client = new \SoapClient($url);
    }
    
    /**
     * Permet de passer un appel SOAP.
     * 
     * @param unknown $methode Le nom de la fonction a appeler.
     * @param unknown $args Les arguments à passer à SOAP (array)
     * @return mixed La reponse SOAP.
     */
    public function call($methode, $args) {
    	$token = $this->container->get('request')->getSession()->get('token');
    	
    	if (isset($token))
    		$this->client->__setCookie('PHPSESSID', $token);
    	
    	try {
    		$tab = $this->client->__soapCall($methode, $args);
    		return $tab;
    	}
    	catch(\SoapFault $e) { // En cas d'erreur
    		throw $e;
    	}
    }
    
    /**
     * Permet de se logger sur le serveur distant.
     * 
     * TODO Vérifier arguments
     * @param unknown $usr Le nom d'utilisateur.
     * @param unknown $passwd Le mot de passe.
     */
    public function login($usr, $passwd) {
    	$this->client->__setCookie('PHPSESSID', uniqid()); // On génére un numero pour la session
    	 
    	try {
    		// On demande à se logger
    		$result = $this->client->__soapCall('login', array('username' => $usr, 'passwd' => $passwd));
    		$result_json = json_decode($result, true);
    	} catch (\SoapFault $e) {
    		throw $e;
    	}
    	
    	$result_json = json_decode($result, true);
    	$token = $result_json['token'];
    	$this->container->get('request')->getSession()->set('token', $token); // On met le token dans la session
    	
    	$role = array();
    	$role[0] = $result_json['role']; // Récupération du role pour créer la session symfony
    	$token = new UsernamePasswordToken($usr, $passwd,'main', $role);
    	$context = $this->container->get('security.context');
    	$context->setToken($token);
    }
}