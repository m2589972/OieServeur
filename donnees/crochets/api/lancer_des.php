<?php

add_action("rest_api_init", function () {
	
	register_rest_route("OieServeur", "lancer_des", [
		"methods" => "POST",
		"args" => [
			"jeton_utilisateur" => [
				"required" => TRUE,
			],
		],
		"permission_callback" => "__return_true",
		"callback" => function (\WP_REST_Request $requete) {
			
			// utilisateur
			$utilisateur = apply_filters("OieServeur/utilisateur/api", NULL, $requete);
			
			
			// partie en cours
			$partie = get_post($utilisateur->id_partie_en_cours);
			
			$identifiants_joueurs = array_keys($partie->utilisateurs);
			$tour_joueur = (int) $partie->tour_joueur;
			
			
			// vérification du joueur qui lance les dés
			if ($utilisateur->ID !== $identifiants_joueurs[$tour_joueur]) {
				return rest_ensure_response(FALSE);
			}
			
			
			$donnees_nouvelle_etape = [
				"id_joueur" => $utilisateur->ID,
				"id_partie" => $partie->ID,
				"messages" => [],
				"des" => [],
			];
			
			
			// lancement des dés
			
			foreach (range(1, (int) $partie->nombre_des) as $lance) {
				
				$resultat = mt_rand(1, 6);
				
				$md5 = unpack("n", md5(microtime(), TRUE));
				
				if (1 === $md5[1] % 2) { // 2e tirage en fonction de l'heure
					$resultat = mt_rand(1, 6);
				}
				
				
				$donnees_nouvelle_etape["des"][] = $resultat;
				
			}
			
			
			// nouvelle étape : joueur suivant
			
			$tour_joueur++;
			
			$tour_joueur %= count($identifiants_joueurs);
			$donnees_nouvelle_etape["tour_joueur"] = $tour_joueur;
			$donnees_nouvelle_etape["id_joueur_suivant"] = $identifiants_joueurs[$tour_joueur];
			
			
			// nouvelle étape : nouvelles positions
			
			$positions = $partie->positions;
			$ancienne_position = $positions[$utilisateur->ID];
			
			$somme_des = array_sum($donnees_nouvelle_etape["des"]);
			$positions[$utilisateur->ID] += $somme_des;
			
			$cases = $partie->cases;
			$longeur = count($cases);
			
			if ($positions[$utilisateur->ID] >= $longeur) {
				$donnees_nouvelle_etape["messages"][] = "trop_loin";
				$positions[$utilisateur->ID] = 2 * $longeur - $positions[$utilisateur->ID] - 2;
			}
			
			$donnees_nouvelle_etape["positions"] = $positions;
			
			$type_case = $cases[$positions[$utilisateur->ID]];
			
			
			// nouvelle étape : filtre et création de l'objet
			
			foreach ([
				"OieServeur/logique/nouvelle_etape",
				"OieServeur/logique/nouvelle_etape/case_$type_case",
			] as $filtre) {
				
				$donnees_nouvelle_etape = apply_filters(
					  $filtre
					, $donnees_nouvelle_etape
					, $utilisateur
					, $partie
					, $requete
				);
				
			}
			
			
			// déplacement en cas de case occupée
			
			foreach ($donnees_nouvelle_etape["positions"] as $id_joueur => $position) {
				
				if ($id_joueur === $utilisateur->ID) {
					continue;
				}
				
				if ($position === $donnees_nouvelle_etape["positions"][$utilisateur->ID]) {
					$donnees_nouvelle_etape["positions"][$id_joueur] = $ancienne_position;
					$donnees_nouvelle_etape["messages"][] = "case_occupee";
				}
				
			}
			
			
			// redéfinition pour ne pas que les filtres modifient cela
			$donnees_nouvelle_etape["id_partie"] = $partie->ID;
			$donnees_nouvelle_etape["id_joueur"] = $utilisateur->ID;
			
			
			$id_etape = apply_filters(
				  "OieServeur/etape/creation"
				, NULL
				, $donnees_nouvelle_etape
			);
			
			
			// réponse
			
			$reponse = [
				"etapes" => [$donnees_nouvelle_etape],
				"timestamp" => (int) get_post_meta($id_etape, "timestamp", TRUE)
			];
			
			
			return rest_ensure_response($reponse);
			
		},
	]); // FIN register_rest_route("OieServeur", "lancer_des", [
	
	
}); // add_action("rest_api_init", function () {


