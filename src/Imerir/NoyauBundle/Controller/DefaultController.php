<?php

namespace Imerir\NoyauBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class DefaultController extends Controller
{
    public function authentificationAction()
    {
    	return $this->render('ImerirNoyauBundle:Default:authentification.html.twig');
    }
    
    public function checkLoginAction()
    {
    		$query = $this->getRequest();
    		$nom = $query->get('utilisateur');
    		$mot_de_passe = $query->get('mot_de_passe');
    
    		// Récupération du service soap et demande de login
    		// TODO gérer les soapfault
    		$soap = $this->get('noyau_soap');
    		$soap->login($nom, $mot_de_passe);
    		return $this->redirect($this->generateUrl('imerir_noyau_index'));
//     	return $this->render('ImerirNoyauBundle:Default:index.html.twig', array('result_menu' => $menu_sous_menu));
    }
    
}
