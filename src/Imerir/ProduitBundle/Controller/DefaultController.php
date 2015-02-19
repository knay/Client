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

    public function ajoutProduitAction()
    {
        $soap = $this->get('noyau_soap');
        //on récupère toutes les lignes produits
        $return_lp = $soap->call('getLigneProduit', array('count' => 0,'offset' => 0, 'nom' => ''));
        $lignesProduitsretour = json_decode($return_lp);

        //on récupère tous les produits
        $return_produits = $soap->call('getProduit',array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
        $produitsRetour = json_decode($return_produits);

        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        return $this->render('ImerirProduitBundle::creerProduit.html.twig', array('ligne_produit' => $lignesProduitsretour,'produits'=>$produitsRetour));
    }

    public function execAjoutProduitAction()
    {

        $query = $this->get('request');
        $nom = $query->request->get('nomProduit');
        $ligneProduit = $query->request->get('nomLigneProduit');

        $soap = $this->get('noyau_soap');
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        $return = $soap->call('ajoutProduit',array('nom' => $nom,'ligneProduit' => $ligneProduit));

        //on récupère toutes les lignes produits
        $return_lp = $soap->call('getLigneProduit', array('count' => 0,'offset' => 0, 'nom' => ''));
        $lignesProduitsretour = json_decode($return_lp);

        //on récupère tous les produits
        $return_produits = $soap->call('getProduit',array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
        $produitsRetour = json_decode($return_produits);

        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        return $this->render('ImerirProduitBundle::creerProduit.html.twig', array('ligne_produit' => $lignesProduitsretour,'produits'=>$produitsRetour));


    }
}
