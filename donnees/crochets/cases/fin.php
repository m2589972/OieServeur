<?php

add_filter("OieServeur/logique/nouvelle_etape/case_fin",
function ($donnees_nouvelle_etape, $utilisateur, $partie, $requete) {
	
	$donnees_nouvelle_etape["messages"][] = "fin";
	
	$donnees_nouvelle_etape["tour_joueur"] = -1;
	$donnees_nouvelle_etape["id_joueur_suivant"] = 0;
	
	
	update_post_meta($partie->ID, "etat", "finie");
	update_user_meta($utilisateur->ID, "id_partie_en_cours", "");
	
	
	return $donnees_nouvelle_etape;
	
}, 10, 4);


