<?php

add_filter("OieServeur/etape/creation", function ($_, $donnees_etape) {
	
	$constantes = apply_filters("OieServeur/constantes", NULL);
	
	
	$donnees_etape_objet = [
		"post_type" => $constantes["type_etape"],
		"post_status" => "pending",
	];
	
	$id_etape = wp_insert_post($donnees_etape_objet);
	
	
	$id_partie = $donnees_etape["id_partie"];
	
	$codes_partie = [
		"positions",
		"tour_joueur",
		"timestamp",
	];
	
	
	$donnees_etape["timestamp"] = time();
	
	foreach ($donnees_etape as $code => $valeur) {
		
		if (in_array($code, $codes_partie)) {
			update_post_meta($id_partie, $code, $valeur);
		}
		
		update_post_meta($id_etape, $code, $valeur);
		
	}
	
	
	
	// si message "fini" -> status partie "finie"
	
	
	return $id_etape;
	
}, 10, 2);


add_action("wp_loaded", function () {
	
	$constantes = apply_filters("OieServeur/constantes", NULL);
	
	register_post_type(
		  $constantes["type_etape"]
		,
		[
			"public" => FALSE,
			"show_ui" => FALSE,
			"map_meta_cap" => TRUE,
			"capability_type" => $constantes["type_etape"],
		]
	);
	
	
}); // FIN add_action("wp_loaded", function () {


add_action("OieServeur/version_actuelle", function ($version_actuelle) {
	
	if (1 <= $version_actuelle) {return;}
	
	
	$constantes = apply_filters("OieServeur/constantes", NULL);
	
	do_action("OieServeur/etapes/ajout_permissions", $constantes["role_administrateur"]);
	
	
});


add_action("OieServeur/etapes/ajout_permissions", function ($code_role) {
	
	// Ajout des permissions
	// Ces permissions sont ajoutées dans la base de données donc elles resteront associées
	// aux rôles même si l'extension est désactivée.
	
	$role = get_role($code_role);
	
	
	$liste_permissions = [
		"edit_posts",
		"edit_others_posts",
		"delete_posts",
		"delete_others_posts",
	];
	
	
	$constantes = apply_filters("OieServeur/constantes", NULL);
	
	$type_etape = get_post_type_object($constantes["type_etape"]);
	
	foreach ($liste_permissions as $p) {
		$role->add_cap($type_etape->cap->$p);
	}
	
	
}); // FIN add_action("OieServeur/etapes/ajout_permissions", function ($code_role) {


