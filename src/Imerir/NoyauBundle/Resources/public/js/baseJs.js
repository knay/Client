$( window ).resize(function() {
    resize_element();
});

function resize_element()
{
	var hauteur_ecran = parseInt($("body").css("height").replace("px",""));
	var hauteur_header = parseInt($("#header").css("height").replace("px",""));
	var largeur_ecran = parseInt($("body").css("width").replace("px",""));
	var largeur_menu = parseInt($("#menu").css("width").replace("px",""));
	$('#contenu').css("height",(hauteur_ecran - hauteur_header) + "px");
	$('#affichage').css("width",(largeur_ecran - largeur_menu ) + "px");
	$('#affichage').css("height",(hauteur_ecran - hauteur_header ) + "px");
	$("#fieldset_ajout").css("width",(largeur_ecran - largeur_menu ) + "px");
	$("#fieldset_recherche").css("width",(largeur_ecran - largeur_menu ) + "px");
	
}

/**
 * Fonction permettant de réagir lors du click sur un
 * élément du menu. Un effet fait apparaitre le sous 
 * menu correspondant.
 */

$id_select = "";
//lorsqu'un element est selectionne dans le menu
$( "#menu" ).children( "li" ).click(function () {
//	alert('click menu');
	//on check si on a deja cliquer sur un element avant
	//oui
	
	if($id_select != ""){
//		alert('oui');
		//on compare l'element selectionner au dernier selectioner
		//si c'est le meme on referme sinon on ouvre le sous menu
		$id_select_back = $id_select;
		$id_select_now = $(this).attr("id");
		//meme
		if($id_select_now == $id_select_back ){
//			alert('meme');
			//on cache le sous menu.
			cacherSousMenu($id_select_now);
			//plus de selected
			$( this ).toggleClass( "selected" );
			//init de la variable select
			$id_select = "";
		}
		//differrent
		else {
//			alert('pas meme');
			chercherToutToggleClass();
			//cacher l'ancien menu selected
			cacherSousMenu($id_select_back);
			$( this ).toggleClass( "selected" );
			//affiche le new sous menu
			afficheSousMenu($id_select_now);
			//id_select devient la nouvelle valeur selected.
			$id_select = $id_select_now;
		}
	}
	//non
	else {
//		alert('non');
		//passe l'element en selected pour Css
		$( this ).toggleClass( "selected" );
		//on recupere l'id selectionner
		$id_select = $(this).attr("id");
		//appel fonction affiche le sous menu en fonction de l'id
		afficheSousMenu($id_select);
	}
	
});

/**
 * Evenement qui ce produit lorsque l'utilisateur click
 * sur le header ou sur l'affichage.
 */
$("#header").click(function (){
	cacher_menu_nimporte_ou();
});

$('#affichage').click(function (){
	cacher_menu_nimporte_ou();
});

/**
 * Cette fonction permet de cacher
 * le sous menu qui est actuellement ouvert
 */
function cacher_menu_nimporte_ou(){
	chercherToutToggleClass();
	cacherSousMenu($id_select);
	$id_select = "";
}

/**
 * Cette fonction permet de rechercher tous les
 * element du menu qui sont selected, et de les enlever
 */
function chercherToutToggleClass(){
	$(".selected").each( function() {
		$( this ).toggleClass( "selected" );
	});
}

/**
 * 
 * @param $id_menu_select
 */
function afficheSousMenu($id_menu_select){
	switch($id_menu_select){
	case 'client':
		$( '#2' ).show( "slide" );
		break;
	case 'fournisseur':
		$( '#4' ).show( "slide" );
		break;
	case 'produit':
		$( '#5' ).show( "slide" );
		break;
	case 'vente':
		$( '#6' ).show( "slide" );
		break;
	}
};

function cacherSousMenu($id_menu_cacher){
	switch($id_menu_cacher){
	case 'client':
		$( "#2:visible" ).hide( "slide" );
		break;
	case 'fournisseur':
		$( "#4:visible" ).hide( "slide" );
		break;
	case 'produit':
		$( "#5:visible" ).hide( "slide" );
		break;
	case 'vente':
		$( "#6:visible" ).hide( "slide" );
		break;
	}
}