<?php

namespace Imerir\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InventaireController extends Controller
{
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap');
    	
    	//on récupère tous les produits
    	$return_produits = $soap->call('getProduit', array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
    	$produitsRetour = json_decode($return_produits);

    	if (isset($produitsRetour[0]->p))
    		$retSoapAttributs = $soap->call('getAttributFromNomProduit', array('nom'=>$produitsRetour[0]->p));
    	else
    		$retSoapAttributs = '';
    	
    	$return_menu = $soap->call('getMenu', array());
    	$menu_sous_menu = json_decode($return_menu);
    	
        return $this->render('ImerirStockBundle::inventaire.html.twig', 
        	   array('produit'=>$produitsRetour,
        			 'attributs'=>json_decode($retSoapAttributs),
        	   		 'result_menu' => $menu_sous_menu
               ));
    }
    
    public function saveAction() {
    	$soap = $this->get('noyau_soap');
    	$req = $this->getRequest()->request;
    	$tabArticle = array();
    	
    	//on récupère tous les produits
    	$return_produits = $soap->call('getProduit', array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
    	$produitsRetour = json_decode($return_produits);
    	
    	if (isset($produitsRetour[0]->p))	
	    	$retSoapAttributs = $soap->call('getAttributFromNomProduit', array('nom'=>$produitsRetour[0]->p));
    	else 
    		$retSoapAttributs = '';
    	
    	// On va parser la request pour former un tableau avec les articles de l'inventaire propre
    	foreach ($req as $key => $value) {
    		$clef = explode('_', $key);
    		if ($clef[0] === 'produit') { // Si c'est le nom du produit concerné
    			$tabArticle[intval($clef[1])]['produit'] = $value;
    		}
    		else if ($clef[0] === 'code') { // Si c'est le code barre
    			$tabArticle[intval($clef[1])]['codeBarre'] = $value;
    		}
    		else if ($clef[0] === 'quantite') { // Si c'est la quantite
    			$tabArticle[intval($clef[1])]['quantite'] = intval($value);
    		}
    		else if ($clef[0] === 'caract') { // Si c'est une valeur d'attribut
    			$attributs = explode('_', $value);
    			$tabArticle[intval($clef[1])]['attributs'][$attributs[0]] = $attributs[1];
    		}
    	}
    	$resultat = $soap->call('faireInventaire', array('articles'=> json_encode($tabArticle)));
    	
    	$return_menu = $soap->call('getMenu', array());
    	$menu_sous_menu = json_decode($return_menu);
    	
    	return $this->render('ImerirStockBundle::inventaire.html.twig',
    			array('produit'=>$produitsRetour,
    				  'attributs'=>json_decode($retSoapAttributs)
    			));
    }
}
