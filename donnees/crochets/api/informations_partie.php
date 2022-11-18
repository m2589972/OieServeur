<?php

add_action("rest_api_init", function () {
	
	register_rest_route("OieServeur", "informations_partie", [
		"methods" => "POST",
		"args" => [
			"jeton_utilisateur" => [
				"required" => TRUE,
			],
		],
		"permission_callback" => "__return_true",
		"callback" => function (\WP_REST_Request $requete) {
			
			$reponse = apply_filters("OieServeur/partie/traitement_requete_api", NULL, $requete);
			
			return rest_ensure_response($reponse);
			
		},
	]); // FIN register_rest_route("OieServeur", "informations_partie", [
	
	
}); // add_action("rest_api_init", function () {


