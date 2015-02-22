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
    	
    	$attributs = $soap->call('getAttributFromNomProduit', array('nom'=>$produitsRetour[0]->p));

    	$return_menu = $soap->call('getMenu', array());
    	$menu_sous_menu = json_decode($return_menu);
    	
        return $this->render('ImerirStockBundle::inventaire.html.twig', 
        	   array('produit'=>$produitsRetour,
        			 'attributs'=>json_decode($attributs),
        	   		'result_menu' => $menu_sous_menu
               ));
    }
}
