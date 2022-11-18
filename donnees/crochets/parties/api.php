<?php

add_filter("OieServeur/partie/traitement_requete_api", function ($_, \WP_REST_Request $requete) {
	
	
	// utilisateur
	$utilisateur = apply_filters("OieServeur/utilisateur/api", NULL, $requete);
	
	
	// traitement particulier
	
	$attributs_requete = $requete->get_attributes();
	
	if (isset($attributs_requete["traitement"])) {
		$attributs_requete["traitement"]($requete, $utilisateur);
	}
	
	
	// réponse
	
	$partie_en_cours_api = apply_filters("OieServeur/partie_en_cours/api"
		, NULL, $utilisateur);
	
	$reponse = [
		"partie_en_cours" => $partie_en_cours_api,
	];
	
	
	return $reponse;
	
}, 10, 2);


add_filter("OieServeur/partie_en_cours/api", function ($_, $utilisateur) {
	
	
	$partie = get_post($utilisateur->id_partie_en_cours);
	
	if (	!isset($partie)
		||	("pending" !== $partie->post_status)
	) {
		return NULL;
	}
	
	
	$identifiants_joueurs = array_keys($partie->utilisateurs);
	
	$cases = $partie->cases;
	
	$tour_joueur = (int) $partie->tour_joueur;
	
	$id_joueur_suivant = 
		(-1 === $tour_joueur) // fin de la partie
			? 0
			: $identifiants_joueurs[$tour_joueur]
	;
	
	$partie_en_cours = [
		"id_partie" => $partie->ID,
		"titre" => $partie->post_title,
		"cases" => $cases,
		"nombre_des" => (int) $partie->nombre_des,
		"nombre_joueurs" => (int) $partie->nombre_joueurs,
		"id_joueur" => $id_joueur_suivant,
		"etat" => $partie->etat,
		"utilisateurs" => $partie->utilisateurs,
		"positions" => $partie->positions,
		"timestamp" => $partie->timestamp,
	];
	
	
	return $partie_en_cours;
	
}, 10, 2);


