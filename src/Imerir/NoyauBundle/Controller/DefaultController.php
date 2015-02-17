<?php

namespace Imerir\NoyauBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	
        return $this->render('ImerirNoyauBundle:Default:index.html.twig');
    }
    
    public function authentificationAction()
    {
    	return $this->render('ImerirNoyauBundle:Default:authentification.html.twig');
    }
    
    public function testAction()
    {
    	return $this->render('ImerirNoyauBundle:Default:test.html.twig');
    }
    
    public function getindexAction()
    {
    	$query = $this->getRequest();
    	$nom = $query->get('utilisateur');
    	$groupe = $query->get('mot_de_passe');
    	return $this->render('ImerirNoyauBundle:Default:index.html.twig',array('utilisateur' => $nom,'groupe' => $groupe));
    }
}
