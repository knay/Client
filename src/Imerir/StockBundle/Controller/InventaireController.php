<?php

namespace Imerir\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/** 
 * Controlleur permettant de réagir à toutes les demande concernant l'inventaire (à 
 * l'exception des requêtes AJAX).
 * 
 * ROUTES : /inventaire
 *          /inventaire/save
 */
class InventaireController extends Controller
{
	/**
	 * Action permettant de réagir lorsque l'utilisateur accède à la page d'inventaire.
	 * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
	 */
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap');
    	
    	// On récupère tous les produits pour les afficher dans un <select>
    	$return_produits = $soap->call('getProduit', array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
    	$produitsRetour = json_decode($return_produits);

    	// S'il y a des produits, on récupère également les attributs du premier 
    	// (parce que c'est celui qui est selectionné au départ)
    	if (isset($produitsRetour[0]->p)) 
    		$retSoapAttributs = $soap->call('getAttributFromNomProduit', array('nom'=>$produitsRetour[0]->p));
    	else
    		$retSoapAttributs = '';
    	
    	$return_menu = $soap->call('getMenu', array()); // On récup le menu/sous-menu
    	$menu_sous_menu = json_decode($return_menu); 
    	
        return $this->render('ImerirStockBundle::inventaire.html.twig', 
        	                  array('produit'     => $produitsRetour,
        			                'attributs'   => json_decode($retSoapAttributs),
        	   		                'result_menu' => $menu_sous_menu));
    }
    
    /**
     * Action appéelée lorsque l'utilisateur enregistre son inventaire.
     * @return \Symfony\Component\HttpFoundation\Response La réponse HTML (la même que pour l'index action).
     */
    public function saveAction() {
    	$soap = $this->get('noyau_soap');
    	$req = $this->getRequest()->request;
    	$tabArticle = array();
    	
    	// On récupère tous les produits pour les afficher dans un <select>
    	$return_produits = $soap->call('getProduit', array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
    	$produitsRetour = json_decode($return_produits);
    	
    	// S'il y a des produits, on récupère également les attributs du premier
    	// (parce que c'est celui qui est selectionné au départ)
    	if (isset($produitsRetour[0]->p))	
	    	$retSoapAttributs = $soap->call('getAttributFromNomProduit', array('nom'=>$produitsRetour[0]->p));
    	else 
    		$retSoapAttributs = '';
    	
    	// On va parser la request pour former un tableau avec les articles de l'inventaire propre
    	foreach ($req as $key => $value) {
    		$clef = explode('_', $key);
    		
    		if (isset($clef[1])) {
	    		$tabArticle[intval($clef[1])]['attributs'] = array();
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
    	}
    	 
    	$soap->call('faireInventaire', array('articles'=> json_encode($tabArticle), 'avecPrix'=>false)); // On enregistre toutes les données de l'inventaire
    	
    	$return_menu = $soap->call('getMenu', array()); // On récup le menu/sous-menu
    	$menu_sous_menu = json_decode($return_menu);
    	
    	return $this->render('ImerirStockBundle::inventaire.html.twig',
			    			  array('produit'     => $produitsRetour,
			    			 	    'attributs'   => json_decode($retSoapAttributs),
			    			 	    'result_menu' => $menu_sous_menu));
    }
}
