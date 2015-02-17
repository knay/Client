<?php

namespace Imerir\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function ajoutAttributAction()
    {
        ini_set("soap.wsdl_cache_enabled", 0);
        //todo mettre l'url dans un twig de ressources
        $client = new \SoapClient('http://localhost/alba/web/app_dev.php/soap');
        $client->__setCookie('PHPSESSID', uniqid());
        $result = $client->__soapCall('login', array('username'=>'alba','passwd'=>'alba'));
        echo $result;
        return $this->render('ImerirProduitBundle::ajoutAttribut.html.twig');
    }

    /**
     * fonction pour créer un produit
     */
    public function creerProduitAction(){
        //TODO ajouter un service qui gere le client soap
        ini_set("soap.wsdl_cache_enabled", 0);
        $client = new \SoapClient('http://localhost/serveur/web/app_dev.php/soap');
        $sessToken = $this->container->get('request')->getSession()->get('token');
        $client->__setCookie('PHPSESSID',$sessToken);
        /*
        $result = $client->__soapCall('newproduct');
        */
        return $this->render('ImerirProduitBundle::creerProduit.html.twig', array('utilisateur' => 'jojo','groupe' => 'toto'));
    }
}
