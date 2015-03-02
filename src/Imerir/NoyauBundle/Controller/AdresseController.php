<?php

namespace Imerir\NoyauBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 */
class AdresseController extends Controller
{
	public function ajoutAdresseAction(){
		$soap = $this->get('noyau_soap');
		/*
		$query = $this->get('request');

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


		$return = $soap->call('getFournisseurs',array('count' => 0,'offset' => 0, 'nom' => $nom, 'email'=>$email, 'telephone_portable'=>$telephone_portable));
		$liste_fournisseurs = json_decode($return);
		*/
		//on recupere le menu et sous menu
		$return_menu = $soap->call('getMenu', array());
		$menu_sous_menu = json_decode($return_menu);


		//return $this->render('ImerirFournisseurBundle::ajoutFournisseur.html.twig', array('liste_fournisseurs'=>$liste_fournisseurs,'result_menu' => $menu_sous_menu));

		return $this->render('ImerirNoyauBundle:Default:ajoutAdresse.html.twig',array('result_menu'=>$menu_sous_menu));

	}
}
