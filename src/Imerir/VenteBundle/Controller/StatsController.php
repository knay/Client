<?php

namespace Imerir\VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatsController extends Controller
{
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$query = $this->get('request');
    	$valeur_comparaison = $query->request->get('comparaison');
    	$menu_sous_menu = array();
    	$stats = array();
    	$mois='';
    	$annee='';
    	$erreur = '';
    	$date = $query->request->get('date');
    	$axeX_n_moins_un = array();
    	$axeY_n = array();
    	$axeY_n_moins_un = array();
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	//Si une date est selectionner on recupere le mois et l'ann�e
    	if($date != ''){
    		$mois = $this->before('-',$date);
    		$annee = $this->after('-',$date);
    	}
    	
    	// L'utilisateur fait une recherche par date
    	if($date != ''){
    		try {
    			$return_moyenne_defaut = $soap->call('statsVenteMoyenneParMoisParDefaut', array($mois,$annee)); 
    			$stats = json_decode($return_moyenne_defaut);
    		}
    		catch(\SoapFault $e) {
    			$erreur.=$e->getMessage();
    		}
    	}
    	// L'utilisateur ne fait pas de recherche sur la date donc par defaut on prend le mois courant
    	else{
    		try {
    			$return_moyenne_defaut = $soap->call('statsVenteMoyenneParMoisParDefaut', array($mois,$annee)); 
    			$stats = json_decode($return_moyenne_defaut);
    		}
    		catch(\SoapFault $e) {
    			$erreur.=$e->getMessage();
    		}
    		
    		foreach ($stats[0] as $valeur){
    			array_push($axeY_n, $valeur->montant_n);
    		}
    		
    		foreach ($stats[1] as $valeur){
    			array_push($axeX_n_moins_un, $valeur->jour_n_moins_un);
    			array_push($axeY_n_moins_un, $valeur->montant_n_moins_un);
    		}
    	}

    	return $this->render('ImerirVenteBundle::statsVenteMois.html.twig', array('result_menu' => $menu_sous_menu,
    			                                                         'axeX_n_moins_un' => $axeX_n_moins_un,
    			                                                         'axeY_n' => $axeY_n,
															    		 'axeY_n_moins_un' => $axeY_n_moins_un,
    		                	                                         'erreur' => $erreur));
    }
    
    public function after($caractere, $date_entiere)
    {
    	if (!is_bool(strpos($date_entiere, $caractere)))
    		return substr($date_entiere, strpos($date_entiere,$caractere)+strlen($caractere));
    }
    
    
    public function before($caractere, $date_entiere)
    {
        return substr($date_entiere, 0, strpos($date_entiere, $caractere));
    }
    
    public function topVenteAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$menu_sous_menu = array();
    	$erreur = '';
    	$stats = array();
    
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	 
    	try {
    		$return_moyenne = $soap->call('statsVenteTopVente', array('nbTop' => 3)); // On récupère le menu/sous-menu
    		$stats = json_decode($return_moyenne);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    
    	return $this->render('ImerirVenteBundle::statsTopVente.html.twig', array('result_menu' => $menu_sous_menu,
    																			 'stats' => $stats,
																    			 'erreur' => $erreur));
    }

}
