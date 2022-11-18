<?php

add_action("wp_loaded", function () {
	
	
	$options = get_option("OieServeur");
	
	if (!isset($options["version"])) {
		
		if (FALSE === $options) {
			$options = [];
		}
		
		$options["version"] = 0;
		
		update_option("OieServeur", $options);
		
	}
	
	
	do_action("OieServeur/version_actuelle", $options["version"]);
	
	
	$version_extension = apply_filters("OieServeur/version_extension", NULL);
	
	$options = get_option("OieServeur");
	$options["version"] = $version_extension;
	update_option("OieServeur", $options);
	
	
}, 500);


