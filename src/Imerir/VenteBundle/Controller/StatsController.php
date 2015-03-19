<?php

namespace Imerir\VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatsController extends Controller
{
    public function indexAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$query = $this->get('request');
    	$menu_sous_menu = array();
    	$stats = array();
    	$mois_legende = array();
    	$annee_legende = array();
    	$mois='';
    	$annee='';
    	$erreur = '';
    	$date = $query->request->get('date');
    	$axeX_n_moins_un = array();
    	$axeY_n = array();
    	$axeY_n_moins_un = array();
    	
    	$mois_demander = date('m');
    	$annee_demander =  date('Y');
    	
    	
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
    			$return_moyenne_defaut = $soap->call('statsVenteMoyenneParMois', array($mois,$annee)); 
    			$stats = json_decode($return_moyenne_defaut);
//     			print_r($stats);
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
    		
    		$mois_demander = $mois;
    		$annee_demander = $annee;
    		
    	}
    	// L'utilisateur ne fait pas de recherche sur la date donc par defaut on prend le mois courant
    	else{
    		try {
    			$return_moyenne_defaut = $soap->call('statsVenteMoyenneParMois', array($mois,$annee)); 
    			$stats = json_decode($return_moyenne_defaut);
//     			print_r($stats);
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

    	array_push($mois_legende,$mois_demander);
    	array_push($annee_legende,$annee_demander);
    	return $this->render('ImerirVenteBundle::statsVenteMois.html.twig', array('result_menu' => $menu_sous_menu,
    			                                                         'axeX_n_moins_un' => $axeX_n_moins_un,
    			                                                         'axeY_n' => $axeY_n,
															    		 'axeY_n_moins_un' => $axeY_n_moins_un,
    																	 'mois'=>$mois_legende,
    																	 'annee'=>$annee_legende,
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
    
    /**
     * Permet de recuperer les statistiques du chiffre d'affaire par mois sur une ann�e,
     * ainsi que la comapraison a l'ann�e n-1.
     */
    public function statsVenteAnnuelleAction(){
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$query = $this->get('request');
    	$menu_sous_menu = array();
    	$annee_legende = array();
    	$stats = array();
    	$erreur = '';
    	$date = $query->request->get('date');
    	$axeX_n_moins_un = array();
    	$axeY_n = array();
    	$axeY_n_moins_un = array();
    	 
    	$annee_demander =  date('Y');
    	
    	try {
    		$return_menu = $soap->call('getMenu', array()); // On récupère le menu/sous-menu
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    	//Si une date est selectionner on recupere le mois et l'ann�e
    	if($date != '' && strlen($date) < 4){
    		$erreur = "Impossible d'avoir une date inf�rieur � 4 caract�res";
    	}
    	else{
	    	// L'utilisateur fait une recherche par date
	    	if($date != ''){
	    		try {
	    			echo $date;
	    			$return_moyenne_defaut = $soap->call('statsVenteMoyenneParAnnee', array($date));
	    			$stats = json_decode($return_moyenne_defaut);
	    			    			print_r($stats);
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
	    		
	    		$annee_demander = $date;
	    		 
	    	}
	    	// L'utilisateur ne fait pas de recherche sur la date donc par defaut on prend le mois courant
	    	else{
	    		try {
	    			$date = '';
	    			$return_moyenne_defaut = $soap->call('statsVenteMoyenneParAnnee', array($date));
	    			$stats = json_decode($return_moyenne_defaut);
// 	    			    			print_r($stats);
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
    	}
    	
    	array_push($annee_legende,$annee_demander);
    	return $this->render('ImerirVenteBundle::statsVenteAnnee.html.twig', array('result_menu' => $menu_sous_menu,
    			'axeX_n_moins_un' => $axeX_n_moins_un,
    			'axeY_n' => $axeY_n,
    			'axeY_n_moins_un' => $axeY_n_moins_un,
    			'annee'=>$annee_legende,
    			'erreur' => $erreur));
    	
    }
    
    public function topVenteMoisAction()
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
    		$return_moyenne = $soap->call('statsVenteTopVente', array('nbMois' => 0)); // On récupère le menu/sous-menu
    		$stats = json_decode($return_moyenne);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    
    	return $this->render('ImerirVenteBundle::statsTopVente.html.twig', array('result_menu' => $menu_sous_menu,
    																			 'stats' => $stats,
																    			 'erreur' => $erreur));
    }
    
    public function topVenteTroisMoisAction()
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
    		$return_moyenne = $soap->call('statsVenteTopVente', array('nbMois' => 3)); // On récupère le menu/sous-menu
    		$stats = json_decode($return_moyenne);
    	}
    	catch(\SoapFault $e) {
    		$erreur.=$e->getMessage();
    	}
    
    	return $this->render('ImerirVenteBundle::statsTopVente.html.twig', array('result_menu' => $menu_sous_menu,
    			'stats' => $stats,
    			'erreur' => $erreur));
    }

    public function topVenteSixMoisAction()
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
    		$return_moyenne = $soap->call('statsVenteTopVente', array('nbMois' => 6)); // On récupère le menu/sous-menu
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
