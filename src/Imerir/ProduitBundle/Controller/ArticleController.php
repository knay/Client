<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Controlleur permettant de gérer les attributs et les valeurs attributs.
 * 
 * ROUTES : /article
 *          /article/save
 */
class ArticleController extends Controller
{
	/**
	 * Action permettant de sauvegarder les modifications sur un article.
	 * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
	 */
	public function saveArticleAction () {
		$soap = $this->get('noyau_soap');
		$erreur = '';
		
		$req = $this->getRequest()->request;
		$query = $this->getRequest()->query;
		$tabArticle = array();
		$tabRech = array();
		$tabArticle['attributs'] = array();
		$codeBarre = '';
		if ($req->get('codeBarre') !== null)
			$codeBarre = $req->get('codeBarre');
		
		// On va parser la request pour former un tableau avec les articles de l'inventaire propre
		foreach ($req as $key => $value) {
			if ($key === 'produit') { // Si c'est le nom du produit concerné
				$tabArticle['produit'] = $value;
			}
			else if ($key === 'code') { // Si c'est le code barre
				$tabArticle['codeBarre'] = $value;
			}
			else if ($key === 'prixClient') { // Si c'est la quantite
				$tabArticle['prixClient'] = $value;
			}
			else if ($key === 'prixFournisseur') { // Si c'est la quantite
				$tabArticle['prixFournisseur'] = $value;
			}
			else if ($key === 'quantite') { // Si c'est la quantite
				if($value > 0) {
					$tabArticle['quantite'] = (int)$value;
				}
			}
			else if (substr($key, 0, strlen('caract')) === 'caract') { // Si c'est une valeur d'attribut
				$attributs = explode('_', $value);
				$tabArticle['attributs'][$attributs[0]] = $attributs[1];
			}
		}
		
		if (isset($tabArticle['codeBarre'])) { // Si on a mit un code barre;.. c'est le minimum pour insérer
			try {
				$return_produits = $soap->call('modifArticle', array('article'=>json_encode($tabArticle)));
			}
			catch(\SoapFault $e) {
				$erreur.=$e->getMessage();
			}
		}
		
		try {
			// On récupère tous les produits pour les afficher dans un <select>
			$return_produits = $soap->call('getProduit', array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
			$produitsRetour = json_decode($return_produits);
			
			$return_all_ligne_produit = $soap->call('getAllLigneProduit',array()); // On recupere toutes les lignes produits pour la recherche
			$all_ligne_produit = json_decode($return_all_ligne_produit);
		}
		catch(\SoapFault $e) {
			$erreur.=$e->getMessage();
		}
		
		try {
			$lp = '';
			$produit = '';
			if ($$query->get('nomLigneProduit') !== null)
				$lp = $query->get('nomLigneProduit');
			if ($query->get('nomProduit') !== null)
				$produit = $query->get('nomProduit');
			// On va chercher tous les articles en fonction des critères de recherche
			$resultRecherche = $soap->call('rechercheArticle', array('nomLigneProduit'=>$lp, 'ligneProduit'=>$produit));
			$tabRech = json_decode($resultRecherche);
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
		
		return $this->render('ImerirProduitBundle::article.html.twig', array('produit' => $produitsRetour,
																			 'result_menu' => $menu_sous_menu,
																			 'erreur' => $erreur,
																			 'result_all_ligne_produit' => $all_ligne_produit,
																			 'tab_recherche' => $tabRech,
				                                                             'codeBarre' => $codeBarre));
	}
	
	/**
	 * Action appélée lorsque à la page de modification d'un attribut et de ses valeurs.
	 * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
	 */
    public function modifArticleAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$erreur = ''; // En cas d'erreur
    	$produitsRetour = array();
    	$menu_sous_menu = array();
    	$tabRech = array();
    	$req = $this->getRequest()->request;
    	$query = $this->getRequest()->query;
    	$codeBarre = '';
    	if ($req->get('codeBarre') !== null)
    		$codeBarre = $req->get('codeBarre');
    	
    	try {
    		// On récupère tous les produits pour les afficher dans un <select>
    		$return_produits = $soap->call('getProduit', array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
    		$produitsRetour = json_decode($return_produits);
    		
    		$return_all_ligne_produit = $soap->call('getAllLigneProduit', array()); // On recupere toutes les lignes produits pour la recherche
    		$all_ligne_produit = json_decode($return_all_ligne_produit);
    		
    		$lp = '';
    		$produit = '';
    		if ($query->get('nomLigneProduit') !== null)
    			$lp = $query->get('nomLigneProduit');
    		if ($req->get('nomProduit') !== null)
    			$produit = $query->get('nomProduit');
    		// On va chercher tous les articles en fonction des critères de recherche
    		$resultRecherche = $soap->call('rechercheArticle', array('nomLigneProduit'=>$lp, 'ligneProduit'=>$produit)); 
    		$tabRech = json_decode($resultRecherche);
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
    	
        return $this->render('ImerirProduitBundle::article.html.twig', array('produit' => $produitsRetour, 
        		                                                             'result_menu' => $menu_sous_menu,
        		                                                             'erreur' => $erreur,
        		                                                             'result_all_ligne_produit' => $all_ligne_produit,
        																	 'tab_recherche' => $tabRech,
        																	 'codeBarre' => $codeBarre));
    }
}
