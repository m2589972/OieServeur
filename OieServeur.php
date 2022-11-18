<?php
/*
Plugin Name: OieServeur
Version: 1
*/

if (!function_exists("add_action")) {
	echo "extension";
	exit();
}


add_filter("OieServeur/configuration", function ($_) {
	
	return [
		"cases" => [
			"simple", "simple", "simple", "simple", "simple",
			"pont",   "simple", "simple", "simple", "simple",
			"simple", "simple", "simple", "simple", "simple",
			"simple", "simple", "simple", "simple", "simple",
			"simple", "simple", "simple", "simple", "simple",
			"simple", "simple", "simple", "simple", "simple",
		],
		"nombre_des" => 2,
		"nombre_joueurs" => [2, 3, 4],
		
	];
	
});


add_filter("OieServeur/constantes", function ($_) {
	
	return [
		"type_partie" => "partie",
		"type_etape" => "etape",
		"role_administrateur" => "administrator",
	];
	
});


add_action("wp_loaded", function () {
	
	// configuration : wp_loaded priorité 500, pour se lancer après les déclarations des objets et taxinomies
	require "donnees/crochets/configuration.php";
	
	
	require "donnees/crochets/logique.php";
	require "donnees/crochets/cases.php";
	
	require "donnees/crochets/api.php";
	
	require "donnees/crochets/utilisateurs.php";
	
	require "donnees/crochets/parties.php";
	
	require "donnees/crochets/etapes.php";
	
	
	
}, 2);


add_filter("OieServeur/base_extension", function ($_) {
	return __DIR__;
});
add_filter("OieServeur/url_extension", function ($_) {
	return plugins_url("", __FILE__);
});


add_filter("OieServeur/version_extension", function ($_) {
	
	if (!isset($GLOBALS["OieServeur"]["version_extension"])) {
		
		$data = get_file_data(__FILE__, ["version" => "Version"]);
		$GLOBALS["OieServeur"]["version_extension"] = $data["version"];
		
	}
	
	
	return $GLOBALS["OieServeur"]["version_extension"];
	
});


