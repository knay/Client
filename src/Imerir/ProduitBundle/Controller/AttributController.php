<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Controlleur permettant de gérer les attributs et les valeurs attributs.
 * 
 * ROUTES : /produits/attribut
 *          /produits/attribut/save
 */
class AttributController extends Controller
{
	/**
     * Action appelée lorsque l'on sauvegarde les valeurs d'un attribut.
     * 
     * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
     */
    public function supprimerAttributAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$erreur = ''; // En cas d'erreur
    	$req = $this->getRequest()->request;
    	$id = 0;
    	
    	if (null !== $req->get('id'))
    		$id = $req->get('id');
		
		try {
			$ret = $soap->call('supprimerAttribut', array('id' => $id)); // On modif/ajoute l'attribut sur le serveur SOAP
		}
		catch(\SoapFault $e) {
			$erreur.=$e->getMessage();
		}
		
		try {
			$args = array('nom' => '', 'idLigneProduit' => 0, 'idAttribut' => 0, 'avecValeurAttribut' => true, 'avecLigneProduit' => false);
			$ret = $soap->call('getAttribut', $args); // On récup les attributs pour affichage en bas (juste le nom pas les valeurs)
			$jsonValeurAttribut = json_decode($ret);
		}
		catch(\SoapFault $e) {
		$erreur.=$e->getMessage();
		}
		
    	try {
    		$args = array('count' => 0, 'offset' => 0, 'nom' => '');
    		$return = $soap->call('getLigneProduit', $args); // On récupère toutes les lignes produits pour affichage dans le <select>
    		$jsonLigneProduit = json_decode($return);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récup menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	return $this->render('ImerirProduitBundle::attribut.html.twig', array('ligne_produit' => $jsonLigneProduit,
    			 															  'lst_attribut' => $jsonValeurAttribut,
    			 															  'result_menu' => $menu_sous_menu, 
    			                                                              'modification' => false,
    			                                                              'erreur' => $erreur));
    }
	
	/**
	 * Action appélée lorsque à la page de modification d'un attribut et de ses valeurs.
	 * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
	 */
    public function modifAttributAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$erreur = ''; // En cas d'erreur
    	$modification = false;
    	
    	$nomRech = $this->getRequest()->query->get('rechNom'); // Si on fait une recher sur le nom de l'article
    	if (null === $nomRech)
    		$nomRech = '';
    	
    	$jsonValeurAttribut = array();
    	try {
    		$args = array('nom' => $nomRech, 'idLigneProduit' => 0, 'idAttribut' => 0, 'avecValeurAttribut' => true, 'avecLigneProduit' => false);
    		$ret = $soap->call('getAttribut', $args); // On récupère tous les attributs depuis le SOAP (juste les attributs, avec leurs valeurs, pour affichage en bas d'écran)
    		$jsonValeurAttribut = json_decode($ret);
   		}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	$id = $this->getRequest()->request->get('id');
    	$jsonValeurAttributAll = array();
    	if ($id !== null) { // Si on accéde au détail d'un attribut, on vas chercher toutes ces info
    		try {
	    		$args = array('nom' => '', 'idLigneProduit' => 0, 'idAttribut' => (int)$id, 'avecValeurAttribut' => true, 'avecLigneProduit' => true);
	    		$ret = $soap->call('getAttribut', $args); // On récup toutes les infos de l'attributs depuis le SOAP
	    		$jsonValeurAttributAll = json_decode($ret);
	    		$modification = true;
	   		}
	    	catch(\SoapFault $e) {
	    		$erreur.=$e->getMessage();
	    	}
    	}
    	
    	try {
    		$args = array('count' => 0, 'offset' => 0, 'nom' => '');
    		$return = $soap->call('getLigneProduit', $args); // On récupère toutes les lignes produits pour affichage dans le <select>
    		$jsonLigneProduit = json_decode($return);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}	
   		catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
        return $this->render('ImerirProduitBundle::attribut.html.twig', array('ligne_produit' => $jsonLigneProduit, 
        		                                                              'lst_attribut' => $jsonValeurAttribut,
        		                                                              'detail_attribut' => $jsonValeurAttributAll,
        																	  'result_menu' => $menu_sous_menu,
        		                                                              'modification' => $modification,
        		                                                              'erreur' => $erreur));
    }
    
    /**
     * Action appelée lorsque l'on sauvegarde les valeurs d'un attribut.
     * 
     * TODO : gérer erreurs d'insertion et de récup...
     * 
     * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
     */
    public function saveAttributAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$erreur = ''; // En cas d'erreur
    	$req = $this->getRequest()->request;
    	
    	if (null !== $req->get('nom'))
    		$nom = $req->get('nom'); // Le nom de la ligne produit
    	else 
    		$nom = ''; // TODO erreur... pas normal...
    	
    	if (null !== $req->get('id'))
    		$id = $req->get('id');
    	else 
    		$id = 0;
    	
    	$attributs = array();
    	$ligneProduit = array();
    	
    	// On va parser la request pour distinguer les attributs des ligne de produits
		foreach ($this->getRequest()->request as $key => $value) {
			if (substr($key, 0, strlen('attribut')) === 'attribut') { // Si c'est un attribut
				array_push($attributs, $value);
			}
			else if (substr($key, 0, strlen('lg_produit')) === 'lg_produit') { // Si c'est une ligne produit
				array_push($ligneProduit, $value);
			}
		}
		
		try {
			$args = array('nom' => $nom, 'lignesProduits' => json_encode($ligneProduit), 'attributs' => json_encode($attributs), 'id' => $id);
			$ret = $soap->call('setAttribut', $args); // On modif/ajoute l'attribut sur le serveur SOAP
		}
		catch(\SoapFault $e) {
			$erreur.=$e->getMessage();
		}
		
		try {
			$args = array('nom' => '', 'idLigneProduit' => 0, 'idAttribut' => 0, 'avecValeurAttribut' => true, 'avecLigneProduit' => false);
			$ret = $soap->call('getAttribut', $args); // On récup les attributs pour affichage en bas (juste le nom pas les valeurs)
			$jsonValeurAttribut = json_decode($ret);
		}
		catch(\SoapFault $e) {
		$erreur.=$e->getMessage();
		}
		
    	try {
    		$args = array('count' => 0, 'offset' => 0, 'nom' => '');
    		$return = $soap->call('getLigneProduit', $args); // On récupère toutes les lignes produits pour affichage dans le <select>
    		$jsonLigneProduit = json_decode($return);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récup menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	
    	return $this->render('ImerirProduitBundle::attribut.html.twig', array('ligne_produit' => $jsonLigneProduit,
    			 															  'lst_attribut' => $jsonValeurAttribut,
    			 															  'result_menu' => $menu_sous_menu, 
    			                                                              'modification' => false,
    			                                                              'erreur' => $erreur));
    }
}
