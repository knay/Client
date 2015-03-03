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
	public function saveArticleAction () {
		$soap = $this->get('noyau_soap');
		$erreur = '';
		
		$req = $this->getRequest()->request;
		$tabArticle = array();
		$tabArticle['attributs'] = array();
		
		// On va parser la request pour former un tableau avec les articles de l'inventaire propre
		foreach ($req as $key => $value) {
			if ($key === 'produit') { // Si c'est le nom du produit concerné
				$tabArticle['produit'] = $value;
			}
			else if ($key === 'code') { // Si c'est le code barre
				$tabArticle['codeBarre'] = $value;
			}
			else if ($key === 'prix') { // Si c'est la quantite
				$tabArticle['prix'] = $value;
			}
			else if (substr($key, 0, strlen('caract')) === 'caract') { // Si c'est une valeur d'attribut
				$attributs = explode('_', $value);
				$tabArticle['attributs'][$attributs[0]] = $attributs[1];
			}
		}
		
		try {
			$return_produits = $soap->call('modifArticle', array('article'=>json_encode($tabArticle)));
			
		}
		catch(\SoapFault $e) {
			$erreur.=$e->getMessage();
		}
		
		try {
			// On récupère tous les produits pour les afficher dans un <select>
			$return_produits = $soap->call('getProduit', array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
			$produitsRetour = json_decode($return_produits);
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
																			 'erreur' => $erreur));
	}
	
	/**
	 * Action appélée lorsque à la page de modification d'un attribut et de ses valeurs.
	 * 
	 * TODO: gérer erreur d'accés SOAP
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
	 */
    public function modifArticleAction()
    {
    	$soap = $this->get('noyau_soap'); // Récup du client SOAP depuis le service.
    	$erreur = ''; // En cas d'erreur
    	$produitsRetour = array();
    	$menu_sous_menu = array();
    	
    	
    	try {
    		// On récupère tous les produits pour les afficher dans un <select>
    		$return_produits = $soap->call('getProduit', array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
    		$produitsRetour = json_decode($return_produits);
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
        		                                                             'erreur' => $erreur));
    }
}
