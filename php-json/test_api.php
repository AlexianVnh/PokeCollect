<?php

require_once ("pokemon_api.php");

echo "==================================================\n";
echo "TEST 1 : GET POKEMON PAR SON ID\n";
echo "==================================================\n";

print_r(get_pokemon_by_id(1));

echo "==================================================\n";
echo "TEST 2 : GET POKEMON PAR SON NOM\n";
echo "==================================================\n";

print_r(get_pokemon_by_name("ivysaur"));

echo "==================================================\n";
echo "TEST 3 : LISTER TOUT LES POKEMON DE LA BASE\n";
echo "==================================================\n";

//print_r(list_all_pokemon());

echo "==================================================\n";
echo "TEST 4 : LISTER TOUT LES POKEMON D'UN DRESSEUR\n";
echo "==================================================\n";

print_r(list_all_pokemon_from_collector(1));

echo "==================================================\n";
echo "TEST 5 : AJOUTER UN POKEMON A UN DRESSEUR\n";
echo "==================================================\n";

/*
add_pokemon_to_collector(1,102);
add_pokemon_to_collector(1,65);
add_pokemon_to_collector(1,65);
*/

echo "==================================================\n";
echo "TEST 6 : SUPPRIMER UN POKEMON D'UN DRESSEUR\n";
echo "==================================================\n";

/*
delete_pokemon_from_collector("61a157d227e3b");
delete_pokemon_from_collector("61a157d2295a3");
delete_pokemon_from_collector("61a157d22b485");
delete_pokemon_from_collector("61a157d22cae7");
delete_pokemon_from_collector("61a157d22e227");
delete_pokemon_from_collector("61a157d22feab");
*/

echo "==================================================\n";
echo "TEST 7 : CREER UN NOUVEAU DRESSEUR\n";
echo "==================================================\n";

//add_collector(4, "Roboute Guilliman", "M");

echo "==================================================\n";
echo "TEST 8 : SUPPRIMER UN DRESSEUR\n";
echo "==================================================\n";

remove_collector(4);

echo "==================================================\n";
echo "TEST 9 : CHANGER LE SURNOM D'UN POKEMON D'UN DRESSEUR\n";
echo "==================================================\n";

change_pokemon_nickname("61a15780e69fc", "Plante Chelou");

?>