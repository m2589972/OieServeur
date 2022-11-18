<?php

add_filter("OieServeur/logique/nouvelle_etape/case_pont",
function ($donnees_nouvelle_etape, $utilisateur, $partie, $requete) {
	
	$donnees_nouvelle_etape["messages"][] = "pont";
	
	$id_joueur = $donnees_nouvelle_etape["id_joueur"];
	$donnees_nouvelle_etape["positions"][$id_joueur] += 6;
	
	
	return $donnees_nouvelle_etape;
	
}, 10, 4);


