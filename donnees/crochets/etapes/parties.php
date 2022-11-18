<?php

add_filter("OieServeur/etapes/par_partie", function ($_, $id_partie, $timestamp) {
	
	$constantes = apply_filters("OieServeur/constantes", NULL);
	
	$etapes = get_posts([
		"nopaging" => TRUE,
		"post_type" => $constantes["type_etape"],
		"post_status" => "pending",
		"meta_query" => [
			[
				"key" => "id_partie",
				"value" => $id_partie,
			],
			[
				"key" => "timestamp",
				"value" => $timestamp,
				"compare" => ">",
			],
		],
	]);
	
	
	return $etapes;
	
}, 10, 3);


