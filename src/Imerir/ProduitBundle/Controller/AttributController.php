<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AttributController extends Controller
{
    public function modifAttributAction()
    {
    	$soap = $this->get('noyau_soap');
    	
    	$args = array(
    			'idLigneProduit' => 0,
    			'idAttribut' => 0,
    			'avecValeurAttribut' => false,
    			'avecLigneProduit' => false
    	);
    	$ret = $soap->call('getAttribut', $args);
    	$jsonValeurAttribut = json_decode($ret);
    	
    	// TODO REUSSIR A FAIRE CA
    	//$ret = $soap->call('fault', array('nom' => 'dd'));
    	
    	$id = $this->getRequest()->request->get('id');
    	if ($id !== null) {
	    	$args = array(
	    			'idLigneProduit' => 0,
	    			'idAttribut' => (int)$id,
	    			'avecValeurAttribut' => true,
	    			'avecLigneProduit' => true
	    	);
	    	$ret = $soap->call('getAttribut', $args); 
    	}
    	
    	$jsonValeurAttributAll = json_decode($ret);
    	
    	$args = array(
    			'count' => 0,
    			'offset' => 0,
    			'nom' => ''
    	);
    	$return = $soap->call('getLigneProduit', $args);
    	$jsonLigneProduit = json_decode($return);
    	
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig', array('ligne_produit' => $jsonLigneProduit, 
        		                                                                   'lst_attribut' => $jsonValeurAttribut,
        		                                                                   'detail_attribut' => $jsonValeurAttributAll));
    }
    
    public function saveAttributAction()
    {
    	$soap = $this->get('noyau_soap');
    	$req = $this->getRequest()->request;
    	
    	$nom = $req->get('nom');
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
		$args = array(
				'nom' => $nom,
				'lignesProduits' => json_encode($ligneProduit),
				'attributs' => json_encode($attributs),
				'id' => $id
		);
		try {
			$ret = $soap->call('setAttribut', $args);
		} catch (SoapFault $fault) {
			echo 'ereur';
		}
		
		$args = array(
				'idLigneProduit' => 0,
				'idAttribut' => 0,
				'avecValeurAttribut' => false,
				'avecLigneProduit' => false
		);
		$ret = $soap->call('getAttribut', $args);
		$jsonValeurAttribut = json_decode($ret);
		
    	$args = array(
    			'count' => 0,
    			'offset' => 0,
    			'nom' => ''
    	);
    	$return = $soap->call('getLigneProduit', $args);
    	$jsonLigneProduit = json_decode($return);
    	
    	return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig', array('ligne_produit' => $jsonLigneProduit, 'lst_attribut' => $jsonValeurAttribut));
    }
}
