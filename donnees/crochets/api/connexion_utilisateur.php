<?php

add_action("rest_api_init", function () {
	
	register_rest_route("OieServeur", "connexion_utilisateur", [
		"methods" => "POST",
		"args" => [
			"nom_utilisateur" => [
				"required" => TRUE,
			],
		],
		"permission_callback" => "__return_true",
		"callback" => function (\WP_REST_Request $requete) {
			
			$nom_utilisateur = $requete->get_param("nom_utilisateur");
			
			
			// recherche de l'utilisateur
			$utilisateur = apply_filters("OieServeur/utilisateur/connexion", NULL, $nom_utilisateur);
			
			// erreur si le nom d'utilisateur est vide par exemple
			if (is_wp_error($utilisateur)) {
				return rest_ensure_response(NULL);
			}
			
			$reponse = [
				"id_utilisateur" => $utilisateur->ID,
				"jeton_utilisateur" => $utilisateur->jeton,
				"nom_utilisateur" => $utilisateur->display_name,
			];
			
			
			// partie en cours
			
			$partie_en_cours_api = apply_filters("OieServeur/partie_en_cours/api", NULL, $utilisateur);
			
			if (isset($partie_en_cours_api)) {
				
				$reponse["partie_en_cours"] = $partie_en_cours_api;
				
			} else { // si pas de partie en cours : liste des parties en préparation
				
				$reponse["parties_en_preparation"] = [];
				
				$parties_en_preparation = apply_filters(
					"OieServeur/parties/parties_en_preparation", NULL);
				
				foreach ($parties_en_preparation as $partie) {
					$reponse["parties_en_preparation"][$partie->ID] = [
						"titre" => $partie->post_title,
						"utilisateurs" => $partie->utilisateurs,
						"nombre_joueurs" => $partie->nombre_joueurs,
					];
				}
				
			}
			
			
			// réponse
			return rest_ensure_response($reponse);
			
		},
	]); // FIN register_rest_route("OieServeur", "connexion_utilisateur", [
	
	
}); // add_action("rest_api_init", function () {


