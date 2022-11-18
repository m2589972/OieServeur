<?php

add_action("rest_api_init", function () {
	
	register_rest_route("OieServeur", "informations", [
		"methods" => "GET",
		"permission_callback" => "__return_true",
		"callback" => function (\WP_REST_Request $requete) {
			
			$version = apply_filters("OieServeur/version_extension", NULL);
			
			$liste_nombre_joueurs = apply_filters("OieServeur/liste_nombre_joueurs", NULL);
			
			
			$informations = [
				"version" => $version,
				"nombre_joueurs" => $liste_nombre_joueurs,
			];
			
			
			return rest_ensure_response($informations);
			
		},
	]); // FIN register_rest_route("OieServeur", "informations", [
	
	
}); // add_action("rest_api_init", function () {


