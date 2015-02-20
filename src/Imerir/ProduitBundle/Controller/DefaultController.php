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

        $query = $this->get('request');
        $nomRech = $query->request->get('rechProduit');
        $ligneProduitRech = $query->request->get('rechLp');

        if($nomRech === null)
            $nomRechVal = '';
        else
            $nomRechVal=$nomRech;
        if($ligneProduitRech === null)
            $lpRechVal = '';
        else
            $lpRechVal = $ligneProduitRech;

        //on récupère toutes les lignes produits
        $return_lp = $soap->call('getLigneProduit', array('count' => 0,'offset' => 0, 'nom' =>''));
        $lignesProduitsretour = json_decode($return_lp);

        //on récupère tous les produits
        $return_produits = $soap->call('getProduit',array('count'=>0, 'offset'=>0, 'nom'=>$nomRechVal, 'ligneproduit'=>$lpRechVal));
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
        return $this->render('ImerirProduitBundle::creerProduit.html.twig', array('ligne_produit' => $lignesProduitsretour,'produits'=>$produitsRetour,'produit_add'=>$nom,'lp_produit_add'=>$ligneProduit));


    }

    public function modifProduitAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');
        $nomRech = $query->request->get('rechProduit');
        $ligneProduitRech = $query->request->get('rechLp');

        $nom_modif_lp = $query->request->get('nom_lp');
        $id_modif_p = $query->request->get('id_p');
        $nom_modif_p = $query->request->get('nom_p');

        if($nomRech === null)
            $nomRechVal = '';
        else
            $nomRechVal=$nomRech;
        if($ligneProduitRech === null)
            $lpRechVal = '';
        else
            $lpRechVal = $ligneProduitRech;

        //on récupère toutes les lignes produits
        $return_lp = $soap->call('getLigneProduit', array('count' => 0,'offset' => 0, 'nom' =>''));
        $lignesProduitsretour = json_decode($return_lp);

        //on récupère tous les produits
        $return_produits = $soap->call('getProduit',array('count'=>0, 'offset'=>0, 'nom'=>$nomRechVal, 'ligneproduit'=>$lpRechVal));
        $produitsRetour = json_decode($return_produits);

        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        return $this->render('ImerirProduitBundle::creerProduit.html.twig',
            array('ligne_produit' => $lignesProduitsretour,'produits'=>$produitsRetour,
                'nom_modif_lp'=>$nom_modif_lp,'nom_modif_p'=>$nom_modif_p,'id_modif_p'=>$id_modif_p));
    }

    public function execModifProduitAction()
    {

        $query = $this->get('request');

        $nom_modif_p = $query->request->get('nom_modif_p');
        $old_nom_modif_p = $query->request->get('old_nom_modif_p');
        $nom_modif_lp = $query->request->get('nomLigneProduit');
        $id_modif_p = $query->request->get('id_modif_p');

        $soap = $this->get('noyau_soap');
        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        $return = $soap->call('modifProduit',array('nom_lp' => $nom_modif_lp,'nom_p' => $nom_modif_p,'id_p'=>$id_modif_p));

        //on récupère toutes les lignes produits
        $return_lp = $soap->call('getLigneProduit', array('count' => 0,'offset' => 0, 'nom' => ''));
        $lignesProduitsretour = json_decode($return_lp);

        //on récupère tous les produits
        $return_produits = $soap->call('getProduit',array('count'=>0, 'offset'=>0, 'nom'=>'', 'ligneproduit'=>''));
        $produitsRetour = json_decode($return_produits);

        //on appelle la fonction ajoutLigneProduit du serveur soap qui prend en parametre le nom de la ligne produit
        return $this->render('ImerirProduitBundle::creerProduit.html.twig',
            array('ligne_produit' => $lignesProduitsretour,
                'produits'=>$produitsRetour,'old_p'=>$old_nom_modif_p,'new_p'=>$nom_modif_p,'new_lp'=>$nom_modif_lp));


    }
}
