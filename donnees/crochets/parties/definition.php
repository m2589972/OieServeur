<?php

add_filter("OieServeur/partie/creation", function ($_, $donnees_partie) {
	
	$constantes = apply_filters("OieServeur/constantes", NULL);
	
	
	$donnees_partie_objet = [
		"post_type" => $constantes["type_partie"],
		"post_title" => $donnees_partie["titre"],
		"post_status" => "pending",
	];
	
	$id_partie = wp_insert_post($donnees_partie_objet);
	
	
	$cases = apply_filters("OieServeur/cases", NULL);
	$nombre_des = apply_filters("OieServeur/nombre_des", NULL);
	
	update_post_meta($id_partie, "etat", "preparation");
	update_post_meta($id_partie, "cases", $cases);
	update_post_meta($id_partie, "nombre_des", $nombre_des);
	
	update_post_meta($id_partie, "utilisateurs", []);
	update_post_meta($id_partie, "positions", []);
	update_post_meta($id_partie, "timestamp_derniere_etape", 0);
	
	update_post_meta($id_partie, "nombre_joueurs", $donnees_partie["nombre_joueurs"]);
	
	
	return $id_partie;
	
}, 10, 2);


add_action("wp_loaded", function () {
	
	
	$labels = [
		"name" => "Parties",
		"archives" => "Parties",
		"singular_name" => "Partie",
		"add_new" => "Nouvelle partie",
		"add_new_item" => "Partie",
		"edit_item" => "Éditer la partie",
		"new_item" => "Nouvelle partie",
		"view_item" => "Voir la partie",
		"search_items" => "Recherche de partie",
		"not_found" => "Pas de partie trouvée",
		"not_found_in_trash" => "Pas de partie trouvée dans la corbeille",
		"parent_item_colon" => "",
		"edition__mise_a_jour" => "Partie mise à jour",
		"edition__nouvel_objet_cree" => "Nouvelle partie créée",
		"message__objet_singulier" => "partie",
		"message__objet_pluriel" => "parties",
		"selection__tous_les_objets" => "toutes les parties",
	];
	
	$supports = [
		"title",
	];
	
	
	$constantes = apply_filters("OieServeur/constantes", NULL);
	
	register_post_type(
		  $constantes["type_partie"]
		,
		[
			"labels" => $labels,
			"supports" => $supports,
			"public" => FALSE,
			"show_ui" => TRUE,
			"map_meta_cap" => TRUE,
			"capability_type" => $constantes["type_partie"],
			"menu_icon" => "dashicons-groups",
		]
	);
	
	
}); // FIN add_action("wp_loaded", function () {


add_action("OieServeur/version_actuelle", function ($version_actuelle) {
	
	if (1 <= $version_actuelle) {return;}
	
	
	$constantes = apply_filters("OieServeur/constantes", NULL);
	
	do_action("OieServeur/parties/ajout_permissions", $constantes["role_administrateur"]);
	
	
});


add_action("OieServeur/parties/ajout_permissions", function ($code_role) {
	
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
	
	$type_partie = get_post_type_object($constantes["type_partie"]);
	
	foreach ($liste_permissions as $p) {
		$role->add_cap($type_partie->cap->$p);
	}
	
	
}); // FIN add_action("OieServeur/parties/ajout_permissions", function ($code_role) {


