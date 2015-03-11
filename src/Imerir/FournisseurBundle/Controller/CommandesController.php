<?php

namespace Imerir\FournisseurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommandesController extends Controller
{
    public function ajoutCommandeAction()
    {

        $soap = $this->get('noyau_soap');


        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => '', 'email'=>'',
            'telephone_portable'=>'','reference_client'=>'','notes'=>''));
        $liste_fournisseurs = json_decode($return);

        //$count, $offset, $fournisseur_id, $fournisseur_nom, $commande_id, $article_code

        $return_commandes = $soap->call('getCommandesFournisseurs',array('count' => 0,'offset' => 0,'fournisseur_id'=>'',
            'fournisseur_nom'=>'','commande_id'=>'','article_code'=>'' ));

        $liste_commandes = json_decode($return_commandes);
        return $this->render('ImerirFournisseurBundle::ajoutCommandeFournisseur.html.twig',array('result_menu' => $menu_sous_menu,
            'liste_fournisseurs'=>$liste_fournisseurs,'liste_commandes'=>$liste_commandes,'nbLignes'=>0));
    }

}
