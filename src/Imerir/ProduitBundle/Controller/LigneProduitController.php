<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LigneProduitController extends Controller
{
    public function ajoutLigneProduitAction()
    {

        return $this->render('ImerirProduitBundle::ajoutLigneProduit.html.twig', array('utilisateur' => 'jojo','groupe' => 'toto'));
    }

    public function execAjoutLigneProduitAction()
    {
        //TODO ajouter un service qui gere le client soap

        $query = $this->get('request');
        $nom = $query->request->get('nom');


        ini_set("soap.wsdl_cache_enabled", 0);
        $client = new \SoapClient('http://localhost/serveur/web/app_dev.php/soap');
        $sessToken = $this->container->get('request')->getSession()->get('token');
        $client->__setCookie('PHPSESSID',$sessToken);

        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        $result = $client->__soapCall('ajoutLigneProduit',array('nom' => $nom));
        return $this->render('ImerirProduitBundle::AjoutLigneProduit.html.twig', array('utilisateur' => 'jojo','groupe' => 'toto'));
    }

}
