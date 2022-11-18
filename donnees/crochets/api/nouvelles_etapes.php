<?php

add_action("rest_api_init", function () {
	
	register_rest_route("OieServeur", "nouvelles_etapes", [
		"methods" => "POST",
		"args" => [
			"jeton_utilisateur" => [
				"required" => TRUE,
			],
			"date_etape_precedente" => [
				"required" => TRUE,
			],
		],
		"permission_callback" => "__return_true",
		"callback" => function (\WP_REST_Request $requete) {
			
			// utilisateur
			$utilisateur = apply_filters("OieServeur/utilisateur/api", NULL, $requete);
		
			
			// partie en cours
			
			$partie = get_post($utilisateur->id_partie_en_cours);
			
			if (!isset($partie)) {
				return rest_ensure_response([]);
			}
			
			if ("finie" === $partie->etat) {
				update_user_meta($utilisateur->ID, "id_partie_en_cours", "");
			}
			
			
			$timestamp = $requete->get_param("date_etape_precedente");
			
			$etapes = apply_filters(
				  "OieServeur/etapes/par_partie"
				, NULL
				, $partie->ID
				, $timestamp
			);
			
			
			$etapes_api = [];
			
			foreach ($etapes as $etape) {
				
				$timestamp = max($timestamp, $etape->timestamp);
				
				$etapes_api[$etape->ID] = apply_filters(
					  "OieServeur/etape/api"
					, NULL
					, $etape
				);
				
			}
			
			
			$reponse = [
				"etapes" => $etapes_api,
				"timestamp" => $timestamp,
			];
			
			
			return rest_ensure_response($reponse);
			
		},
	]); // FIN register_rest_route("OieServeur", "nouvelles_etapes", [
	
	
}); // add_action("rest_api_init", function () {


