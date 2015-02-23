<?php

namespace Imerir\NoyauBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Le controlleur permettant la gestion de la caisse.
 * ROUTE : /
 */
class CaisseController extends Controller
{
	/**
	 * L'action appelée lorsque l'on demande la page d'accueil qui correspond à la caisse.
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
    public function CaisseAction()
    {
    	$soap = $this->get('noyau_soap');
    	
    	$return_menu = $soap->call('getMenu', array()); // Récup du menu/sous-menu
    	$menu_sous_menu = json_decode($return_menu);
    	
    	return $this->render('ImerirNoyauBundle:Default:index.html.twig', array('result_menu' => $menu_sous_menu));
    }
}
