<?php

add_filter("OieServeur/utilisateur/api", function ($_, \WP_REST_Request $requete) {
	
	$utilisateur = NULL;
	
	$recherche = get_users([
		"meta_key" => "jeton",
		"meta_value" => $requete->get_param("jeton_utilisateur"),
	]);
	
	if (isset($recherche[0])) {
		$utilisateur = $recherche[0];
	}
	
	
	return $utilisateur;
	
}, 10, 2);


add_filter("OieServeur/utilisateur/connexion", function ($_, $nom_utilisateur) {
	
	
	$nom_utilisateur = trim($nom_utilisateur);
	
	
	// retrait des accents
	
	$tls = \Transliterator::createFromRules(
		  "::Any-Latin; ::Latin-ASCII; ::NFD; ::NFC;"
		, \Transliterator::FORWARD
	);
	
	$login = $tls->transliterate($nom_utilisateur);
	
	
	// mise en minuscules
	$login = mb_strtolower($login);
	
	// garder uniquement les lettres, chiffres et les caractères "_.-"
	$login = preg_replace("|[^a-z0-9_.\-]|", "", $login);
	
	
	// recherche de l'utilisateur
	
	$utilisateur = get_user_by("login", $login);
	
	if (FALSE === $utilisateur) {
		
		// création de l'utilisateur
		
		$id_utilisateur = wp_insert_user([
			"user_login" => $login,
			"user_pass" => "",
			"display_name" => $nom_utilisateur,
		]);
		
		// erreur si le nom d'utilisateur est vide par exemple
		if (is_wp_error($id_utilisateur)) {
			return $id_utilisateur;
		}
		
		// récupération de l'objet utilisateur
		$utilisateur = get_user_by("id", $id_utilisateur);
		
	}
	
	
	// nouveau jeton
	$jeton = hash("whirlpool", $login . microtime(TRUE) . rand());
	update_user_meta($utilisateur->ID, "jeton", $jeton);
	
	
	// retour
	return $utilisateur;
	
}, 10, 2);


