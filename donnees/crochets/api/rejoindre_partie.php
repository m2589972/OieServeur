<?php

add_action("rest_api_init", function () {
	
	register_rest_route("OieServeur", "rejoindre_partie", [
		"methods" => "POST",
		"args" => [
			"jeton_utilisateur" => [
				"required" => TRUE,
			],
			"id_partie" => [
				"required" => TRUE,
			],
		],
		"permission_callback" => "__return_true",
		"callback" => function (\WP_REST_Request $requete) {
			
			$reponse = apply_filters("OieServeur/partie/traitement_requete_api", NULL, $requete);
			
			return rest_ensure_response($reponse);
			
		},
		"traitement" => function (\WP_REST_Request $requete, \WP_User $utilisateur) {
			
			$id_partie = $requete->get_param("id_partie");
			
			// association de la partie à l'utilisateur
			do_action("OieServeur/partie/association_utilisateur", $id_partie, $utilisateur);
			
		},
	]); // FIN register_rest_route("OieServeur", "rejoindre_partie", [
	
	
}); // add_action("rest_api_init", function () {


