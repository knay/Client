<?php

namespace Imerir\FournisseurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function ajoutFournisseurAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');
        //TODO ajouter un champs post dans le twig avec recherche fournisseur
        $recherche_fournisseur_nom = $query->request->get('recherche_fournisseur_nom');
        $recherche_fournisseur_email = $query->request->get('recherche_fournisseur_email');
        $recherche_fournisseur_telephone_portable = $query->request->get('recherche_fournisseur_telephone_portable');


        if($recherche_fournisseur_nom===null)
            $nom='';
        else
            $nom=$recherche_fournisseur_nom;
        if($recherche_fournisseur_email===null)
            $email='';
        else
            $email=$recherche_fournisseur_email;
        if($recherche_fournisseur_telephone_portable===null)
            $telephone_portable = '';
        else
            $telephone_portable = $recherche_fournisseur_telephone_portable;

        //TODO ajouter l'action getFournisseurs qui renvoie le nom, le mail et le num de tel
        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => $nom, 'email'=>$email, 'telephone_portable'=>$telephone_portable));
        $liste_fournisseurs = json_decode($return);

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //TODO invoquer le bon twig
        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu' => $menu_sous_menu,'nbAdresse'=>0));
    }

    public function execAjoutFournisseurAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');

        $fournisseur_nom = $query->request->get('nom');
        $fournisseur_email = $query->request->get('email');
        $fournisseur_telephone_portable = $query->request->get('telephone_portable');

        if($fournisseur_nom===null)
            $fournisseur_nom='';

        if($fournisseur_email===null)
            $fournisseur_email='';

        if($fournisseur_telephone_portable===null)
            $fournisseur_telephone_portable===null;

        $soap->call('ajoutFournisseur',array('nom' => $fournisseur_nom, 'email'=>$fournisseur_email, 'telephone_portable'=>$fournisseur_telephone_portable));
        ////////////////////////////////////////////////////////////////////////////
        /**
         * PARTIE ADRESSES
         */
        $adresse_pays = array();
        $adresse_ville = array();
        $adresse_code_postal = array();
        $adresse_num_voie = array();
        $adresse_voie = array();
        $adresse_num_appartement = array();
        $adresse_telephone_fixe = array();


        foreach ($query->request as $key => $value) {
            if (substr($key, 0, strlen('pays')) === 'pays') {
                array_push($adresse_pays, $value);
            }
            if (substr($key, 0, strlen('ville')) === 'ville') {
                array_push($adresse_ville, $value);
            }
            if (substr($key, 0, strlen('code_postal')) === 'code_postal') {
                array_push($adresse_code_postal, $value);
            }
            if (substr($key, 0, strlen('num_voie')) === 'num_voie') {
                array_push($adresse_num_voie, $value);
            }
            if (substr($key, 0, strlen('voie')) === 'voie') {
                array_push($adresse_voie, $value);
            }
            if (substr($key, 0, strlen('num_appartement')) === 'num_appartement') {
                array_push($adresse_num_appartement, $value);
            }
            if (substr($key, 0, strlen('telephone_fixe')) === 'telephone_fixe') {
                array_push($adresse_telephone_fixe, $value);
            }
        }
        //PARTIE OU ON RECUPERE LA REF DU FOURNISSEUR
        $retour_ref_fournisseur = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => $fournisseur_nom,
            'email'=>$fournisseur_email, 'telephone_portable'=>$fournisseur_telephone_portable));

        $ref_fournisseur = json_decode($retour_ref_fournisseur,true);
        $ref_fournisseur_id = $ref_fournisseur[0]["id"];
        //print_r($ref_fournisseur);

        //$est_fournisseur,$ref_id,$pays,$ville, $voie, $num_voie, $code_postal, $num_appartement,$telephone_fixe
        $soap->call('ajoutAdresse',array('est_fournisseur'=>true,'ref_id'=>$ref_fournisseur_id,'pays'=>json_encode($adresse_pays),
            'ville'=>json_encode($adresse_ville),'voie'=>json_encode($adresse_voie),'num_voie'=>json_encode($adresse_num_voie),
            'code_postal'=>json_encode($adresse_code_postal),'num_appartement'=>json_encode($adresse_num_appartement),
            'telephone_fixe'=>json_encode($adresse_telephone_fixe)));

        ////////////////////////////////////////////////////////////////////////////


        //TODO ajouter l'action getFournisseurs qui renvoie le nom, le mail et le num de tel
        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => '', 'email'=>'', 'telephone_portable'=>''));
        $liste_fournisseurs = json_decode($return);
        //insertion du fournisseur

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //TODO invoquer le bon twig
        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu' => $menu_sous_menu));
    }

    public function modifFournisseurAction(){
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');

        $modif_id = $query->request->get('id_f');
        $modif_nom = $query->request->get('nom_f');
        $modif_email = $query->request->get('email_f');
        $modif_telephone_portable = $query->request->get('telephone_portable_f');

        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => '', 'email'=>'', 'telephone_portable'=>''));
        $liste_fournisseurs = json_decode($return);

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);



        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu'=>$menu_sous_menu,'modif_id'=>$modif_id,
            'modif_nom'=>$modif_nom,'modif_email'=>$modif_email,
            'modif_telephone_portable'=>$modif_telephone_portable));

    }

    public function execModifFournisseurAction(){
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');

        $old_id = $query->request->get('old_id');
        $old_nom = $query->request->get('old_nom');
        $new_nom = $query->request->get('new_nom');
        $new_email = $query->request->get('new_email');
        $new_telephone_portable = $query->request->get('new_telephone_portable');

        $soap->call('modifFournisseur',array('id'=>$old_id,
            'nom'=>$new_nom,'email'=>$new_email,'telephone_portable'=>$new_telephone_portable));

        $return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => '', 'email'=>'', 'telephone_portable'=>''));
        $liste_fournisseurs = json_decode($return);

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu'=>$menu_sous_menu,'old_id'=>$old_id,
            'old_nom'=>$old_nom,'new_nom'=>$new_nom,'new_email'=>$new_email,
            'new_telephone_portable'=>$new_telephone_portable));

    }

}
