<?php

namespace Imerir\StockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/** 
 * Controlleur permettant de gérer la quantite de stock
 */
class StockController extends Controller
{
	
	/**
	 * Action appel� lorsqu'on est sur la page des stock
	 * 
	 */
    public function getStockAction()
    {
    	$soap = $this->get('noyau_soap');
    	$return_stock = $soap->call('getStock', array()); // On récup le menu/sous-menu
    	$stock = json_decode($return_stock); 
    	
    	$return_menu = $soap->call('getMenu', array()); // On récup le menu/sous-menu
    	$menu_sous_menu = json_decode($return_menu);
    	
        return $this->render('ImerirStockBundle::stock.html.twig',array('result_stock' => $stock,'result_menu' => $menu_sous_menu));
    }
}
