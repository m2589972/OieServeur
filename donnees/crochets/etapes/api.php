<?php

add_filter("OieServeur/etape/api", function ($_, $etape) {
	
	$id_partie = (int) get_post_meta($etape->ID, "id_partie", TRUE);
	
	$identifiants_joueurs = array_keys(get_post_meta($id_partie, "utilisateurs", TRUE));
	$tour_joueur = (int) $etape->tour_joueur;
	
	$id_joueur_suivant = 
		(-1 === $tour_joueur) // fin de la partie
			? 0
			: $identifiants_joueurs[$tour_joueur]
	;
	
	
	$etape_api = [
		"des" => $etape->des,
		"id_joueur" => (int) $etape->id_joueur,
		"id_joueur_suivant" => $id_joueur_suivant,
		"positions" => $etape->positions,
		"messages" => $etape->messages,
	];
	
	
	return $etape_api;
	
}, 10, 2);


