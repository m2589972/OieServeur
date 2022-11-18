<?php

add_filter("OieServeur/parties/parties_en_preparation", function ($_) {
	
	$constantes = apply_filters("OieServeur/constantes", NULL);
	
	$partie_en_preparation = get_posts([
		"nopaging" => TRUE,
		"post_type" => $constantes["type_partie"],
		"post_status" => "pending",
		"meta_query" => [
			[
				"key" => "etat",
				"value" => "preparation",
			],
		],
	]);
	
	
	return $partie_en_preparation;
	
});


add_action("OieServeur/partie/association_utilisateur", function ($id_partie, $utilisateur) {
	
	// association à l'utilisateur
	update_user_meta($utilisateur->ID, "id_partie_en_cours", $id_partie);
	
	// association à la partie
	$utilisateurs = get_post_meta($id_partie, "utilisateurs", TRUE);
	$utilisateurs[$utilisateur->ID] = $utilisateur->display_name;
	update_post_meta($id_partie, "utilisateurs", $utilisateurs);
	
	
	// état de la partie
	
	$nombre_joueurs = (int) get_post_meta($id_partie, "nombre_joueurs", TRUE);
	
	if (count($utilisateurs) === $nombre_joueurs) {
		
		update_post_meta($id_partie, "etat", "en_cours");
		
		
		$identifiants_joueurs = array_keys($utilisateurs);
		
		$positions = array_fill_keys($identifiants_joueurs, 0);
		update_post_meta($id_partie, "positions", $positions);
		
		update_post_meta($id_partie, "tour_joueur", 0);
		
	}
	
	
}, 10, 2);


