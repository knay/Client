$( document ).ready(function() {
    resize();
});

function resize()
{
	var hauteur_ecran = parseInt($("body").css("height").replace("px",""));
	var hauteur_header = parseInt($("#header").css("height").replace("px",""));
	var largeur_ecran = parseInt($("body").css("width").replace("px",""));
	var largeur_menu = parseInt($("#menu").css("width").replace("px",""));
	$('#contenu').css("height",(hauteur_ecran - hauteur_header) + "px");
	$('#affichage').css("width",(largeur_ecran - largeur_menu ) + "px");
	$('#affichage').css("height",(hauteur_ecran - hauteur_header ) + "px");
	
	$(fieldset).css("width",(largeur_ecran - largeur_menu ) + "px");
	
}

/**
 * Fonction permettant de réagir lors du click sur un
 * élément du menu. Un effet fait apparaitre le sous 
 * menu correspondant.
 */

$id_select = "";
//lorsqu'un element est selectionne dans le menu
$( "#menu" ).children( "li" ).click(function () {

//on check si on a deja cliquer sur un element avant
//oui
if($id_select != ""){
	//alert('oui');
	//on compare l'element selectionner au dernier selectioner
	//si c'est le meme on referme sinon on ouvre le sous menu
	$id_select_back = $id_select;
	$id_select_now = $(this).attr("id");
	
	if($id_select_now == $id_select_back ){
		//alert('meme');
		cacherSousMenu($id_select_now);
		$( this ).toggleClass( "selected" );
		$id_select = "";
	}
	else {
		//alert('pas meme');
		chercherToutToggleClass();
		cacherSousMenu($id_select_back);
		$( this ).toggleClass( "selected" );
		afficheSousMenu($id_select_now);
		$id_select = $id_select_now;
	}
}
//non
else {
	$( this ).toggleClass( "selected" );
	//alert('non');
	//on recupere l'id selectionner
	$id_select = $(this).attr("id");
	//appel fonction affiche le sous menu en fonction de l'id
		afficheSousMenu($id_select);
	}
	
});

function chercherToutToggleClass(){
	$(".selected").each( function() {
	$( this ).toggleClass( "selected" );
	});
}

function afficheSousMenu($id_menu_select){
	switch($id_menu_select){
	case "a":
		$( "#sousMenu" ).show( "slide" );
		break;
	case "b":
		$( "#sousMenu" ).show( "slide" );
		break;
	case "c":
		$( "#sousMenu" ).show( "slide" );
		break;
	case "d":
		$( "#sousMenu" ).show( "slide" );
		break;
	case "e":
		$( "#sousMenu" ).show( "slide" );
		break;
	case "f":
		$( "#sousMenu" ).show( "slide" );
		break;
	case "g":
		$( "#sousMenu" ).show( "slide" );
			break;
	}
};

function cacherSousMenu($id_menu_cacher){
	switch($id_menu_cacher){
	case "a":
		$( "#sousMenu:visible" ).removeAttr( "slide" ).fadeOut();
		break;
	case "b":
		$( "#sousMenu:visible" ).removeAttr( "slide" ).fadeOut();
		break;
	case "c":
		$( "#sousMenu:visible" ).removeAttr( "slide" ).fadeOut();
		break;
	case "d":
		$( "#sousMenu:visible" ).removeAttr( "slide" ).fadeOut();
		break;
	case "e":
		$( "#sousMenu:visible" ).removeAttr( "slide" ).fadeOut();
		break;
	case "f":
		$( "#sousMenu:visible" ).removeAttr( "slide" ).fadeOut();
		break;
	case "g":
		$( "#sousMenu:visible" ).removeAttr( "slide" ).fadeOut();
			break;
		}
}