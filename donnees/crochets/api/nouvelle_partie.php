<?php

add_action("rest_api_init", function () {
	
	register_rest_route("OieServeur", "nouvelle_partie", [
		"methods" => "POST",
		"args" => [
			"jeton_utilisateur" => [
				"required" => TRUE,
			],
			"titre" => [
				"required" => TRUE,
			],
			"nombre_joueurs" => [
				"required" => TRUE,
			],
		],
		"permission_callback" => "__return_true",
		"callback" => function (\WP_REST_Request $requete) {
			
			$reponse = apply_filters("OieServeur/partie/traitement_requete_api", NULL, $requete);
			
			return rest_ensure_response($reponse);
			
		},
		"traitement" => function (\WP_REST_Request $requete, \WP_User $utilisateur) {
			
			$titre_partie = $requete->get_param("titre");
			$nombre_joueurs = $requete->get_param("nombre_joueurs");
			
			
			$liste_nombre_joueurs = apply_filters("OieServeur/liste_nombre_joueurs", NULL);
			
			if (!in_array($nombre_joueurs, $liste_nombre_joueurs)) {
				$nombre_joueurs = $liste_nombre_joueurs[0];
			}
			
			
			// création de la partie
			
			$donnees_partie = [
				"titre" => $titre_partie,
				"nombre_joueurs" => $nombre_joueurs,
			];
			
			$id_partie = apply_filters("OieServeur/partie/creation", NULL, $donnees_partie);
			
			
			// association de la partie à l'utilisateur
			do_action("OieServeur/partie/association_utilisateur", $id_partie, $utilisateur);
			
		},
	]); // FIN register_rest_route("OieServeur", "nouvelle_partie", [
	
	
}); // add_action("rest_api_init", function () {


