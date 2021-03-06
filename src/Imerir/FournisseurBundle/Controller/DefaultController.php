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
        $recherche_fournisseur_nom = $query->query->get('recherche_fournisseur_nom');
        $recherche_fournisseur_email = $query->query->get('recherche_fournisseur_email');
        $recherche_fournisseur_telephone_portable = $query->query->get('recherche_fournisseur_telephone_portable');
        $recherche_fournisseur_reference_client = $query->query->get('recherche_fournisseur_reference_client');
        $recherche_fournisseur_notes = $query->query->get('recherche_fournisseur_notes');

        $erreur = '';

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
        if($recherche_fournisseur_reference_client===null)
            $reference_client = '';
        else
            $reference_client = $recherche_fournisseur_reference_client;
        if($recherche_fournisseur_notes===null)
            $notes = '';
        else
            $notes = $recherche_fournisseur_notes;

        //TODO ajouter l'action getFournisseurs qui renvoie le nom, le mail et le num de tel
        try {
            $return = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => $nom, 'email' => $email,
                'telephone_portable' => $telephone_portable, 'reference_client' => $reference_client, 'notes' => $notes));
            $liste_fournisseurs = json_decode($return);
        }

        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //TODO invoquer le bon twig
        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,
            'result_menu' => $menu_sous_menu,'nbAdresse'=>0,'erreur'=>$erreur));
    }

    public function execAjoutFournisseurAction()
    {
        $soap = $this->get('noyau_soap');

        $query = $this->get('request');

        $fournisseur_nom = $query->request->get('nom');
        $fournisseur_email = $query->request->get('email');
        $fournisseur_telephone_portable = $query->request->get('telephone_portable');
        $fournisseur_reference_client = $query->request->get('reference_client');
        $fournisseur_notes = $query->request->get('notes');

        $erreur = '';

        if($fournisseur_nom===null)
            $fournisseur_nom='';

        if($fournisseur_email===null)
            $fournisseur_email='';

        if($fournisseur_telephone_portable===null)
            $fournisseur_telephone_portable='';

        if($fournisseur_reference_client===null)
            $fournisseur_reference_client='';
        if($fournisseur_notes===null)
            $fournisseur_notes='';

        try {
            $soap->call('ajoutFournisseur', array('nom' => $fournisseur_nom, 'email' => $fournisseur_email,
                'telephone_portable' => $fournisseur_telephone_portable, 'reference_client' => $fournisseur_reference_client,
                'notes' => $fournisseur_notes));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }
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

        $erreur = '';

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
        //print_r($adresse_pays);
        //PARTIE OU ON RECUPERE LA REF DU FOURNISSEUR
        try {
            $retour_ref_fournisseur = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => $fournisseur_nom,
                'email' => $fournisseur_email, 'telephone_portable' => $fournisseur_telephone_portable, 'reference_client' => $fournisseur_reference_client,
                'notes' => $fournisseur_notes));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        $ref_fournisseur = json_decode($retour_ref_fournisseur,true);
        $ref_fournisseur_id = $ref_fournisseur[0]["id"];
        //print_r($ref_fournisseur);
        if(!empty($adresse_pays[0]) || !empty($adresse_ville[0]) || !empty($adresse_code_postal[0]) || !empty($adresse_voie[0]) ||
            !empty($adresse_num_voie[0]) || !empty($adresse_num_appartement[0]) || !empty($adresse_telephone_fixe[0])) {

            //$est_fournisseur,$ref_id,$pays,$ville, $voie, $num_voie, $code_postal, $num_appartement,$telephone_fixe
            try {
                $soap->call('ajoutAdresse', array('est_fournisseur' => true, 'ref_id' => $ref_fournisseur_id, 'pays' => json_encode($adresse_pays),
                    'ville' => json_encode($adresse_ville), 'voie' => json_encode($adresse_voie), 'num_voie' => json_encode($adresse_num_voie),
                    'code_postal' => json_encode($adresse_code_postal), 'num_appartement' => json_encode($adresse_num_appartement),
                    'telephone_fixe' => json_encode($adresse_telephone_fixe)));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }
        }
        ////////////////////////////////////////////////////////////////////////////


        //TODO ajouter l'action getFournisseurs qui renvoie le nom, le mail et le num de tel
        $erreur = '';
        try {
            $return = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => '', 'email' => '',
                'telephone_portable' => '', 'reference_client' => '', 'notes' => ''));
            $liste_fournisseurs = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }
        //insertion du fournisseur

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //TODO invoquer le bon twig
        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,
            'result_menu' => $menu_sous_menu,'nbAdresse'=>0,'erreur'=>$erreur));
    }

    public function modifFournisseurAction(){
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');

        $modif_id = $query->request->get('id_f');
        $modif_nom = $query->request->get('nom_f');
        $modif_email = $query->request->get('email_f');
        $modif_telephone_portable = $query->request->get('telephone_portable_f');
        $modif_reference_client = $query->request->get('reference_client_f');
        $modif_notes = $query->request->get('notes_f');

        $erreur = '';

        try {
            $return = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => '', 'email' => '',
                'telephone_portable' => '', 'reference_client' => '', 'notes' => ''));
            $liste_fournisseurs = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        $liste_adresses_fournisseur = '';
        //IL faut recuperer dans un tableau toutes les valeurs adresse pour chaque fournisseur
        //$count, $offset,$est_fournisseur,$ref_id, $pays, $ville, $voie, $num_voie, $code_postal, $num_appartement,$telephone_fixe
        if ($modif_id !== null) {
	        try {
	            $return_adresses_fournisseur = $soap->call('getAdresses', array('count' => 0, 'offset' => 0, 'est_fournisseur' => true,
	                'ref_id' => strval($modif_id), 'pays' => '', 'ville' => '', 'voie' => '', 'num_voie' => '',
	                'code_postal' => '', 'num_appartement' => '', 'telephone_fixe' => ''));
	            $liste_adresses_fournisseur = json_decode($return_adresses_fournisseur);
	        }
	        catch(\SoapFault $e) {
	            $erreur .=$e->getMessage();
	        }
        }

        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu'=>$menu_sous_menu,'modif_id'=>$modif_id,
            'modif_nom'=>$modif_nom,'modif_email'=>$modif_email,
            'modif_telephone_portable'=>$modif_telephone_portable,'modif_reference_client'=>$modif_reference_client,
            'modif_notes'=>$modif_notes,'liste_adresses_fournisseur'=>$liste_adresses_fournisseur,
            'nbAdresse'=>0,'erreur'=>$erreur));

    }
    
    public function execSupprFournisseurAction(){
    	$soap = $this->get('noyau_soap');
    	$query = $this->get('request');
    	
    	$old_id = $query->request->get('old_id');
    	
    	$erreur = '';
    	
    	if($old_id != null){
    		try {
    			$soap->call('supprFournisseur', array('id' =>$old_id));
    		}
    		catch(\SoapFault $e) {
    			$erreur .=$e->getMessage();
    		}
    	}
    	
    	try {
    		$return = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => '', 'email' => '',
    				'telephone_portable' => '', 'reference_client' => '', 'notes' => ''));
    		$liste_fournisseurs = json_decode($return);
    	}
    	catch(\SoapFault $e) {
    		$erreur .=$e->getMessage();
    	}
    	
    	//on recupere le menu et sous menu
    	$return_menu = $soap->call('getMenu', array());
    	$menu_sous_menu = json_decode($return_menu);
    	
    	return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,
    			'result_menu'=>$menu_sous_menu,
    			'nbAdresse'=>0,'erreur'=>$erreur));
    	
    	
    }

    public function execModifFournisseurAction(){
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');

        $old_id = $query->request->get('old_id');
        $old_nom = $query->request->get('old_nom');
        $new_nom = $query->request->get('new_nom');
        $new_email = $query->request->get('new_email');
        $new_telephone_portable = $query->request->get('new_telephone_portable');
        $new_reference_client = $query->request->get('new_reference_client');
        $new_notes = $query->request->get('new_notes');

        $erreur = '';

        ////////////////////PARTIE ADRESSES///////////////
        $modif_adresse_est_visible = array();
        $modif_adresse_id = array();
        $modif_adresse_pays = array();
        $modif_adresse_ville = array();
        $modif_adresse_code_postal = array();
        $modif_adresse_num_voie = array();
        $modif_adresse_voie = array();
        $modif_adresse_num_appartement = array();
        $modif_adresse_telephone_fixe = array();

        $adresse_pays = array();
        $adresse_ville = array();
        $adresse_code_postal = array();
        $adresse_num_voie = array();
        $adresse_voie = array();
        $adresse_num_appartement = array();
        $adresse_telephone_fixe = array();


        foreach ($query->request as $key => $value) {
            ////PARTIE MODIFICATION
            if (substr($key, 0, strlen('modif_est_visible')) === 'modif_est_visible') {
                array_push($modif_adresse_est_visible, $value);
            }
            if (substr($key, 0, strlen('modif_id')) === 'modif_id') {
                array_push($modif_adresse_id, $value);
            }
            if (substr($key, 0, strlen('modif_pays')) === 'modif_pays') {
                array_push($modif_adresse_pays, $value);
            }
            if (substr($key, 0, strlen('modif_ville')) === 'modif_ville') {
                array_push($modif_adresse_ville, $value);
            }
            if (substr($key, 0, strlen('modif_code_postal')) === 'modif_code_postal') {
                array_push($modif_adresse_code_postal, $value);
            }
            if (substr($key, 0, strlen('modif_num_voie')) === 'modif_num_voie') {
                array_push($modif_adresse_num_voie, $value);
            }
            if (substr($key, 0, strlen('modif_voie')) === 'modif_voie') {
                array_push($modif_adresse_voie, $value);
            }
            if (substr($key, 0, strlen('modif_num_appartement')) === 'modif_num_appartement') {
                array_push($modif_adresse_num_appartement, $value);
            }
            if (substr($key, 0, strlen('modif_telephone_fixe')) === 'modif_telephone_fixe') {
                array_push($modif_adresse_telephone_fixe, $value);
            }
            ////PARTIE INSERTION DE DONNEES
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
        //print_r($adresse_pays);
        //PARTIE OU ON RECUPERE LA REF DU FOURNISSEUR
        $ref_fournisseur_id = $old_id;
        //print_r($ref_fournisseur);

        //$est_fournisseur,$ref_id,$pays,$ville, $voie, $num_voie, $code_postal, $num_appartement,$telephone_fixe
        //on teste si il y a des valeurs à ajouter
        if(empty($adresse_pays[0]) && empty($adresse_ville[0]) && empty($adresse_code_postal[0]) && empty($adresse_voie[0]) &&
         empty($adresse_num_voie[0]) && empty($adresse_num_appartement[0]) && empty($adresse_telephone_fixe[0])){
            //MODIFICATION
            try {
                $soap->call('modifAdresse', array('est_visible' => json_encode($modif_adresse_est_visible),
                    'id_ad' => json_encode($modif_adresse_id), 'pays' => json_encode($modif_adresse_pays),
                    'ville' => json_encode($modif_adresse_ville), 'voie' => json_encode($modif_adresse_voie),
                    'num_voie' => json_encode($modif_adresse_num_voie),
                    'code_postal' => json_encode($modif_adresse_code_postal),
                    'num_appartement' => json_encode($modif_adresse_num_appartement),
                    'telephone_fixe' => json_encode($modif_adresse_telephone_fixe)));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }

        }
        else{
            //MODIFICATION
            try {
                $soap->call('modifAdresse', array('est_visible' => json_encode($modif_adresse_est_visible),
                    'id_ad' => json_encode($modif_adresse_id), 'pays' => json_encode($modif_adresse_pays),
                    'ville' => json_encode($modif_adresse_ville), 'voie' => json_encode($modif_adresse_voie),
                    'num_voie' => json_encode($modif_adresse_num_voie),
                    'code_postal' => json_encode($modif_adresse_code_postal),
                    'num_appartement' => json_encode($modif_adresse_num_appartement),
                    'telephone_fixe' => json_encode($modif_adresse_telephone_fixe)));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }
            //INSERTION
            try {
                $soap->call('ajoutAdresse', array('est_fournisseur' => true, 'ref_id' => $ref_fournisseur_id, 'pays' => json_encode($adresse_pays),
                    'ville' => json_encode($adresse_ville), 'voie' => json_encode($adresse_voie), 'num_voie' => json_encode($adresse_num_voie),
                    'code_postal' => json_encode($adresse_code_postal), 'num_appartement' => json_encode($adresse_num_appartement),
                    'telephone_fixe' => json_encode($adresse_telephone_fixe)));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }
        }
        //////////////////////////////////////////////////
        try {
            $soap->call('modifFournisseur', array('id' => $old_id,
                'nom' => $new_nom, 'email' => $new_email, 'telephone_portable' => $new_telephone_portable,
                'reference_client' => $new_reference_client, 'notes' => $new_notes));
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        try {
            $return = $soap->call('getFournisseurs', array('count' => 0, 'offset' => 0, 'nom' => '', 'email' => '',
                'telephone_portable' => '', 'reference_client' => '', 'notes' => ''));
            $liste_fournisseurs = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu'=>$menu_sous_menu,'old_id'=>$old_id,
            'old_nom'=>$old_nom,'new_nom'=>$new_nom,'new_email'=>$new_email,
            'new_telephone_portable'=>$new_telephone_portable,'new_reference_client'=>$new_reference_client,
        'new_notes'=>$new_notes,'nbAdresse'=>0,'erreur'=>$erreur));

    }

}
