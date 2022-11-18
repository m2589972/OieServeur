<?php

add_filter("OieServeur/cases", function ($_) {
	
	$configuration = apply_filters("OieServeur/configuration", NULL);
	
	$cases = array_merge(["debut"], $configuration["cases"], ["fin"]);
	
	
	return $cases;
	
});


add_filter("OieServeur/nombre_des", function ($_) {
	
	$configuration = apply_filters("OieServeur/configuration", NULL);
	
	
	return $configuration["nombre_des"];
	
});


add_filter("OieServeur/liste_nombre_joueurs", function ($_) {
	
	$configuration = apply_filters("OieServeur/configuration", NULL);
	
	
	return $configuration["nombre_joueurs"];
	
});



