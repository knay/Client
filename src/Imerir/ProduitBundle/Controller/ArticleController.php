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
    	
    	// On récupère tous les produits pour les afficher dans un <select>
    	$return_produits = $soap->call('getProduit', array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
    	$produitsRetour = json_decode($return_produits);
    	
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
