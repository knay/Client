<?php

namespace Imerir\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Permet de gérer la modification, la création et la visualisation des contacts.
 * Un contact est un client du commerce.
 * 
 * ROUTES :  /contacts/creation, /contacts/creation/validation, /contacts/modification, 
 *           /contacts/modification/validation
 */
class DefaultController extends Controller
{
	/**
	 * Permet d'ajouter un contact.
	 * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
	 */
    public function ajoutContactAction()
    {
        $soap = $this->get('noyau_soap');
        $erreur = '';

        $query = $this->get('request');
        //TODO ajouter un champs post dans le twig avec recherche contact
        $recherche_contact_nom = $query->query->get('recherche_contact_nom');
        $recherche_contact_prenom = $query->query->get('recherche_contact_prenom');
        $recherche_contact_date_naissance = $query->query->get('recherche_contact_date_naissance');
        $recherche_contact_civilite = $query->query->get('recherche_contact_civilite');
        $recherche_contact_email = $query->query->get('recherche_contact_email');
        $recherche_contact_telephone_portable = $query->query->get('recherche_contact_telephone_portable');
        $recherche_contact_ok_sms = $query->query->get('recherche_contact_ok_sms');
        $recherche_contact_ok_mail = $query->query->get('recherche_contact_ok_mail');
        $recherche_contact_notes = $query->query->get('recherche_contact_notes');

        if($recherche_contact_nom===null)
            $nom='';
        else
            $nom=$recherche_contact_nom;
        if($recherche_contact_email===null)
            $email='';
        else
            $email=$recherche_contact_email;
        if($recherche_contact_telephone_portable===null)
            $telephone_portable = '';
        else
            $telephone_portable = $recherche_contact_telephone_portable;
        if($recherche_contact_prenom===null)
            $prenom='';
        else
            $prenom=$recherche_contact_prenom;
        if($recherche_contact_civilite===null)
            $civilite='';
        else
            $civilite=$recherche_contact_civilite;
        if($recherche_contact_date_naissance===null)
            $date_naissance = '';
        else
            $date_naissance = $recherche_contact_date_naissance;
        if($recherche_contact_ok_sms===null)
            //$int_ok_sms='';
            $ok_sms = '';
        else{
            $ok_sms=$recherche_contact_ok_sms;
            if($ok_sms=='Oui')
                $int_ok_sms=1;
            elseif($ok_sms=='Non')
                $int_ok_sms=0;
            else
                $int_ok_sms='';
        }
        if($recherche_contact_ok_mail===null)
            //$int_ok_mail='';
            $ok_mail='';
        else{
            $ok_mail=$recherche_contact_ok_mail;
            if($ok_mail=='Oui')
                $int_ok_mail=1;
            elseif($ok_mail=='Non')
                $int_ok_mail=0;
            else
                $int_ok_mail='';
        }
        if($recherche_contact_notes===null)
            $notes = '';
        else
            $notes = $recherche_contact_notes;

        //TODO ajouter l'action getContacts qui renvoie le nom, le mail et le num de tel
        try {
            $return = $soap->call('getContacts', array('count' => 0, 'offset' => 0, 'nom' => $nom, 'prenom' => $prenom,
                'date_naissance' => $date_naissance,
                'civilite' => $civilite, 'email' => $email, 'telephone_portable' => $telephone_portable, 'ok_sms' => $ok_sms,
                'ok_mail' => $ok_mail, 'notes' => $notes));
            $liste_contacts = json_decode($return);

            //on recupere le menu et sous menu
            $return_menu = $soap->call('getMenu', array());
            $menu_sous_menu = json_decode($return_menu);
        }
        catch(\SoapFault $e) {
            $erreur .= $e->getMessage();
        }

        //TODO invoquer le bon twig
        return $this->render('ImerirContactBundle::ajoutContact.html.twig', array('liste_contacts'=>$liste_contacts,'result_menu' => $menu_sous_menu,
            'nbAdresse'=>0,'erreur'=>$erreur));
    }

    /**
     * Permet de valider l'enregistrement d'un nouveau contact.
     * @return \Symfony\Component\HttpFoundation\Response La réponse HTML.
     */
    public function execAjoutContactAction()
    {
        $soap = $this->get('noyau_soap');

        $erreur = '';
        $query = $this->get('request');

        $Contact_nom = $query->request->get('nom');
        $Contact_email = $query->request->get('email');
        $Contact_telephone_portable = $query->request->get('telephone_portable');
        $Contact_prenom = $query->request->get('prenom');
        $Contact_date_naissance = $query->request->get('date_naissance');
        $Contact_civilite = $query->request->get('civilite');
        $Contact_ok_sms = $query->request->get('ok_sms');
        $Contact_ok_mail = $query->request->get('ok_mail');
        $Contact_notes = $query->request->get('notes');

        if($Contact_nom===null)
            $Contact_nom='';

        if($Contact_email===null)
            $Contact_email='';

        if($Contact_telephone_portable===null)
            $Contact_telephone_portable===null;

        if($Contact_prenom===null)
            $Contact_prenom='';

        if($Contact_date_naissance===null)
            $Contact_date_naissance='';

        if($Contact_civilite===null)
            $Contact_civilite='';

        if($Contact_ok_sms===null)
            $Contact_ok_sms='';

        if($Contact_ok_mail===null)
            $Contact_ok_mail='';

        if($Contact_notes===null)
            $Contact_notes='';
        
		if($Contact_nom != ''){
	        try{
	            $soap->call('ajoutContact',array('nom' => $Contact_nom,'prenom'=>$Contact_prenom ,
	                'date_naissance'=>$Contact_date_naissance,'civilite'=>$Contact_civilite,
	                'email'=>$Contact_email,
	                'telephone_portable'=>$Contact_telephone_portable,
	                'ok_sms'=>$Contact_ok_sms,'ok_mail'=>$Contact_ok_mail,'notes'=>$Contact_notes));
	        }
	        catch(\SoapFault $e) {
	            $erreur .=$e->getMessage();
	        }
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
        $adresse_type_adresse = array();


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
            if (substr($key, 0, strlen('type_adresse')) === 'type_adresse') {
            	array_push($adresse_type_adresse, $value);
            }
        }
        //print_r($adresse_pays);
        //PARTIE OU ON RECUPERE LA REF DU Contact
        try {
            $retour_ref_Contact = $soap->call('getContacts', array('count' => '', 'offset' => '', 'nom' => $Contact_nom,
                'prenom' => $Contact_prenom, 'date_naissance' => $Contact_date_naissance,
                'civilite' => $Contact_civilite, 'email' => $Contact_email, 'telephone_portable' => $Contact_telephone_portable,
                'ok_sms' => $Contact_ok_sms,
                'ok_mail' => $Contact_ok_mail, 'notes' => $Contact_notes));

            $ref_Contact = json_decode($retour_ref_Contact, true);
            $ref_Contact_id = $ref_Contact[0]["id"];
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }
        
        //print_r($ref_Contact);
        if(!empty($adresse_pays[0]) || !empty($adresse_ville[0]) || !empty($adresse_code_postal[0]) || !empty($adresse_voie[0]) ||
            !empty($adresse_num_voie[0]) || !empty($adresse_num_appartement[0]) || !empty($adresse_telephone_fixe[0])) {

            //$est_Contact,$ref_id,$pays,$ville, $voie, $num_voie, $code_postal, $num_appartement,$telephone_fixe
            try {
                $soap->call('ajoutAdresse', array('est_fournisseur' => false, 'ref_id' => $ref_Contact_id, 'pays' => json_encode($adresse_pays),
                    'ville' => json_encode($adresse_ville), 'voie' => json_encode($adresse_voie), 'num_voie' => json_encode($adresse_num_voie),
                    'code_postal' => json_encode($adresse_code_postal), 'num_appartement' => json_encode($adresse_num_appartement),
                    'telephone_fixe' => json_encode($adresse_telephone_fixe),
                		'type_adresse'=>json_encode($adresse_type_adresse)
                ));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }
        }
        ////////////////////////////////////////////////////////////////////////////


        //TODO ajouter l'action getContacts qui renvoie le nom, le mail et le num de tel
        try {
            $return = $soap->call('getContacts', array('count' => 0, 'offset' => 0, 'nom' => '', 'prenom' => '', 'date_naissance' => '',
                'civilite' => '', 'email' => '', 'telephone_portable' => '', 'ok_sms' => '',
                'ok_mail' => '', 'notes' => ''));
            $liste_Contacts = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }
        //insertion du Contact

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //TODO invoquer le bon twig
        return $this->render('ImerirContactBundle::ajoutContact.html.twig', array('liste_contacts'=>$liste_Contacts,'result_menu' => $menu_sous_menu,
            'nbAdresse'=>0,'erreur'=>$erreur));
    }

    public function modifContactAction(){
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');
        $liste_adresses_Contact = array();

        $erreur = '';

        $modif_id = $query->request->get('id_f');
        $modif_nom = $query->request->get('nom_f');
        $modif_prenom = $query->request->get('prenom_f');
        $modif_date_naissance = $query->request->get('date_naissance_f');
        $modif_civilite = $query->request->get('civilite_f');
        $modif_email = $query->request->get('email_f');
        $modif_telephone_portable = $query->request->get('telephone_portable_f');
        $modif_ok_sms = $query->request->get('ok_sms_f');
        $modif_ok_mail = $query->request->get('ok_mail_f');
        $modif_notes = $query->request->get('notes_f');

        try {
            $return = $soap->call('getContacts', array('count' => 0, 'offset' => 0, 'nom' => '', 'prenom' => '', 'date_naissance' => '',
                'civilite' => '', 'email' => '', 'telephone_portable' => '', 'ok_sms' => '',
                'ok_mail' => '', 'notes' => ''));
            $liste_Contacts = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        //IL faut recuperer dans un tableau toutes les valeurs adresse pour chaque Contact
        //$count, $offset,$est_Contact,$ref_id, $pays, $ville, $voie, $num_voie, $code_postal, $num_appartement,$telephone_fixe
        if ($modif_id !== null) {
	        try {
	            $return_adresses_Contact = $soap->call('getAdresses', array('count' => 0, 'offset' => 0, 'est_fournisseur' => false,
	                'ref_id' => strval($modif_id), 'pays' => '', 'ville' => '', 'voie' => '', 'num_voie' => '',
	                'code_postal' => '', 'num_appartement' => '', 'telephone_fixe' => ''));
	            $liste_adresses_Contact = json_decode($return_adresses_Contact);
	        }
	        catch(\SoapFault $e) {
	            $erreur .=$e->getMessage();
	        }
        }



        return $this->render('ImerirContactBundle::ajoutContact.html.twig', array('liste_contacts'=>$liste_Contacts,'result_menu'=>$menu_sous_menu,
            'modif_id'=>$modif_id,
            'modif_nom'=>$modif_nom,'modif_prenom'=>$modif_prenom,'modif_date_naissance'=>$modif_date_naissance,
            'modif_civilite'=>$modif_civilite,
            'modif_email'=>$modif_email,
            'modif_telephone_portable'=>$modif_telephone_portable,
            'modif_ok_sms'=>$modif_ok_sms,
            'modif_ok_mail'=>$modif_ok_mail,'modif_notes'=>$modif_notes,'liste_adresses_contact'=>$liste_adresses_Contact,
            'nbAdresse'=>0,'erreur'=>$erreur));

    }
    
    public function execSupprContactAction(){
    	$soap = $this->get('noyau_soap');
    	$query = $this->get('request');
    	
    	$erreur = '';
    	
    	//on recupere l'id du contact avant de le rendre invisible (suppression)
    	$old_id = $query->request->get('old_id');
    	
    	//echo $old_id;
    	
    	if($old_id != null){
    		try{
    			$soap->call('supprContact',array('id'=>$old_id));
    		}
    		catch(\SoapFault $e) {
    			$erreur .=$e->getMessage();
    		}
    	}
    	try {
    		$return = $soap->call('getContacts', array('count' => 0, 'offset' => 0, 'nom' => '', 'prenom' => '',
    				'date_naissance' => '',
    				'civilite' => '', 'email' => '', 'telephone_portable' => '', 'ok_sms' => '',
    				'ok_mail' => '', 'notes' => ''));
    		$liste_contacts = json_decode($return);
    	
    		//on recupere le menu et sous menu
    		$return_menu = $soap->call('getMenu', array());
    		$menu_sous_menu = json_decode($return_menu);
    	}
    	catch(\SoapFault $e) {
    		$erreur .= $e->getMessage();
    	}
    	
    	//TODO invoquer le bon twig
    	return $this->render('ImerirContactBundle::ajoutContact.html.twig', array('liste_contacts'=>$liste_contacts,'result_menu' => $menu_sous_menu,
    			'nbAdresse'=>0,'erreur'=>$erreur));
    	
    }

    public function execModifContactAction(){
        $soap = $this->get('noyau_soap');
        $query = $this->get('request');

        $erreur = '';

        $old_id = $query->request->get('old_id');
        $old_nom = $query->request->get('old_nom');
        $old_prenom = $query->request->get('old_prenom');
        $new_nom = $query->request->get('new_nom');
        $new_prenom = $query->request->get('new_prenom');
        $new_date_naissance = $query->request->get('new_date_naissance');
        $new_civilite = $query->request->get('new_civilite');
        $new_email = $query->request->get('new_email');
        $new_telephone_portable = $query->request->get('new_telephone_portable');
        $new_ok_sms = $query->request->get('new_ok_sms');
        $new_ok_mail = $query->request->get('new_ok_mail');
        $new_notes = $query->request->get('new_notes');

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
        $modif_adresse_type_adresse = array();

        $adresse_pays = array();
        $adresse_ville = array();
        $adresse_code_postal = array();
        $adresse_num_voie = array();
        $adresse_voie = array();
        $adresse_num_appartement = array();
        $adresse_telephone_fixe = array();
        $adresse_type_adresse = array();


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
            if (substr($key, 0, strlen('modif_type_adresse')) === 'modif_type_adresse') {
            	array_push($modif_adresse_type_adresse, $value);
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
            if (substr($key, 0, strlen('type_adresse')) === 'type_adresse') {
            	array_push($adresse_type_adresse, $value);
            }
        }
        //print_r($adresse_pays);
        //PARTIE OU ON RECUPERE LA REF DU Contact
        $ref_contact_id = $old_id;
        //print_r($ref_Contact);

        //$est_Contact,$ref_id,$pays,$ville, $voie, $num_voie, $code_postal, $num_appartement,$telephone_fixe
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
                    'telephone_fixe' => json_encode($modif_adresse_telephone_fixe),
                		'type_adresse'=>json_encode($modif_adresse_type_adresse)
                ));
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
                    'telephone_fixe' => json_encode($modif_adresse_telephone_fixe),
                		'type_adresse'=>json_encode($modif_adresse_type_adresse)
                ));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }
            //INSERTION
            try {
                $soap->call('ajoutAdresse', array('est_fournisseur' => false, 'ref_id' => $ref_contact_id, 'pays' => json_encode($adresse_pays),
                    'ville' => json_encode($adresse_ville), 'voie' => json_encode($adresse_voie), 'num_voie' => json_encode($adresse_num_voie),
                    'code_postal' => json_encode($adresse_code_postal), 'num_appartement' => json_encode($adresse_num_appartement),
                    'telephone_fixe' => json_encode($adresse_telephone_fixe),'type_adresse'=>json_encode($adresse_type_adresse)));
            }
            catch(\SoapFault $e) {
                $erreur .=$e->getMessage();
            }
        }
        //////////////////////////////////////////////////

        if ($old_id !== null) {
	        try {
	            $soap->call('modifContact', array('id' => $old_id,
	                'nom' => $new_nom,
	                'prenom' => $new_prenom,
	                'date_naissance' => $new_date_naissance,
	                'civilite' => $new_civilite,
	                'email' => $new_email,
	                'telephone_portable' => $new_telephone_portable,
	                'ok_sms' => $new_ok_sms, 'ok_mail' => $new_ok_mail, 'notes' => $new_notes));
	        }
	        catch(\SoapFault $e) {
	            $erreur .=$e->getMessage();
	        }
        }

        try {
            $return = $soap->call('getContacts', array('count' => 0, 'offset' => 0, 'nom' => '', 'prenom' => '', 'date_naissance' => '',
                'civilite' => '', 'email' => '', 'telephone_portable' => '', 'ok_sms' => '',
                'ok_mail' => '', 'notes' => ''));
            $liste_Contacts = json_decode($return);
        }
        catch(\SoapFault $e) {
            $erreur .=$e->getMessage();
        }

        //on recupere le menu et sous menu
        $return_menu = $soap->call('getMenu', array());
        $menu_sous_menu = json_decode($return_menu);

        return $this->render('ImerirContactBundle::ajoutContact.html.twig', array('liste_contacts'=>$liste_Contacts,'result_menu'=>$menu_sous_menu,'old_id'=>$old_id,
            'old_nom'=>$old_nom,'new_nom'=>$new_nom,'new_email'=>$new_email,
            'new_telephone_portable'=>$new_telephone_portable,'nbAdresse'=>0,'erreur'=>$erreur));

    }

}

