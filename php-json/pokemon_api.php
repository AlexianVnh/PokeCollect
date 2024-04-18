<?php

$GLOBALS["prefix_path"] = "php-json/";
/**
 * Récupère un pokemon par son id
 * La valeur de retour est un tableau associatif contenant les clefs suivants :
 * identifier => le nom du pokemon
 * height => la taille du pokemon
 * weight => le poids du pokemon
 * base_experience => l'experience de base donnée par ce pokemon lorsqu'il est vaincu
 * @param $id
 * @return array|mixed
 */
function get_pokemon_by_id($id){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."pokemon_data.json");
    $json_data = json_decode($file_content, true);
    if (array_key_exists($id, $json_data)){
        $pok = $json_data[$id];
    }
    else {
        $pok = array(
            "identifier" => "unknown_id",
            "height" => 0,
            "weight" => 0,
            "base_experience" => 0
        );
    }
    return $pok;
}

/**
 * Récupère un pokemon par son nom
 * La valeur de retour est un tableau associatif contenant les clefs suivants :
 * identifier => le nom du pokemon
 * height => la taille du pokemon
 * weight => le poids du pokemon
 * base_experience => l'experience de base donnée par ce pokemon lorsqu'il est vaincu
 * @param $name
 * @return array|mixed
 */
function get_pokemon_by_name($name){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."pokemon_data.json");
    $json_data = json_decode($file_content, true);
    $pok = array(
        "identifier" => "unknown_name",
        "height" => 0,
        "weight" => 0,
        "base_experience" => 0
    );
    foreach ($json_data as $element){
        if ($name == $element["identifier"]){
            $pok = $element;
            // On utilise break pour sortir de la boucle une fois le pokemon trouvé
            // A noter qu'un tel usage de break peut être considéré comme une mauvaise pratique
            break;
        }
    }
    return $pok;
}
/**
 * Récupère tout les pokemon
 * La valeur de retour est un tableau associatif ou les clefs sont les id des pokemons
 * @return mixed
 */
function list_all_pokemon(){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."pokemon_data.json");
    return json_decode($file_content, true);
}

/**
 * Récupère la liste de toutes le pokemon capturés par un dresseur
 * attention, il peut y avoir plusieurs fois le même pokemon si le dresseur
 * à capturé plusieurs fois le même
 * Un champs pokemon_nickname est disponible dans le json des associations
 * pour pouvoir personaliser le surnom d'un pokemon capturé
 * @param $collector_id
 * @return array
 */
function list_all_pokemon_from_collector($collector_id){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."collector_pokemon_association.json");
    $json_data = json_decode($file_content, true);
    $pokemon_list = [];
    foreach ($json_data as $element){
        if ($collector_id == $element["collector_id"]){
            array_push($pokemon_list, $element);
        }
    }
    return $pokemon_list;
}

/**
 * Ajoute un pokemon à un dresseur, cela devient une association, on parle aussi de pokemon capturé
 * @param $collector_id
 * @param $pokemon_id
 */
function add_pokemon_to_collector($collector_id, $pokemon_id){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."collector_pokemon_association.json");
    $json_data = json_decode($file_content, true);
    $new_association["association_id"] = uniqid();
    $new_association["collector_id"] = $collector_id;
    $new_association["pokemon_id"] = $pokemon_id;
    $new_association["pokemon_nickname"] = "";
    array_push($json_data, $new_association);
    $new_file_content = json_encode($json_data);
    file_put_contents($GLOBALS["prefix_path"]."collector_pokemon_association.json", $new_file_content);
}

/**
 * Libère un pokemon capturé d'un dresseur, pour ce faire on supprime simplement l'association
 * @param $association_id
 */
function delete_pokemon_from_collector($association_id){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."collector_pokemon_association.json");
    $json_data = json_decode($file_content, true);
    foreach ($json_data as $key=>$value){
        if ($association_id == $value["association_id"]){
            unset($json_data[$key]);
            // On utilise break pour sortir de la boucle une fois le pokemon trouvé
            // A noter qu'un tel usage de break peut être considéré comme une mauvaise pratique
            break;
        }
    }
    $new_file_content = json_encode($json_data);
    file_put_contents($GLOBALS["prefix_path"]."collector_pokemon_association.json", $new_file_content);
}

/**
 * Ajoute d'un nouveau dresseur à la liste
 * @param $id
 * @param $name
 * @param $gender
 * @throws ErrorException
 */
function add_collector($id, $name, $gender){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."collector_data.json");
    $json_data = json_decode($file_content, true);
    if (array_key_exists($id, $json_data)){
        throw new ErrorException("There is already a collector with this id !");
    }
    else{
        $json_data[$id]["collector_name"] = $name;
        $json_data[$id]["gender"] = $gender;
        $new_file_content = json_encode($json_data);
        file_put_contents($GLOBALS["prefix_path"]."collector_data.json", $new_file_content);
    }
}

/**
 * Supprime un dresseur de la liste ainsi que tout ses pokemon capturés
 * @param $id
 * @throws ErrorException
 */
function remove_collector($id){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."collector_data.json");
    $json_data = json_decode($file_content, true);
    if (array_key_exists($id, $json_data)){
        $pokemon_list = list_all_pokemon_from_collector($id);
        foreach ($pokemon_list as $pok){
            delete_pokemon_from_collector($pok["association_id"]);
        }
        unset($json_data[$id]);
        $new_file_content = json_encode($json_data);
        file_put_contents($GLOBALS["prefix_path"]."collector_data.json", $new_file_content);
    }
    else{
        throw new ErrorException("There is no collector with the id provided");
    }
}

/**
 * Modifie le surnom d'un pokemon capturé
 * @param $association_id
 * @param $nickname
 */
function change_pokemon_nickname($association_id, $nickname){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."collector_pokemon_association.json");
    $json_data = json_decode($file_content, true);
    foreach ($json_data as $key=>$value){
        if ($association_id == $value["association_id"]){
            $json_data[$key]["pokemon_nickname"] = $nickname;
            // On utilise break pour sortir de la boucle une fois le pokemon trouvé
            // A noter qu'un tel usage de break peut être considéré comme une mauvaise pratique
            break;
        }
    }
    $new_file_content = json_encode($json_data);
    file_put_contents($GLOBALS["prefix_path"]."collector_pokemon_association.json", $new_file_content);
}

/**
 * Récupère l'id actuel du dresseur "connecté"
 * @return int
 */
function get_current_id(){
    return $_SESSION["collector_id"];
}



/* MES FONCTIONS PERSONNELLES */
/* MES FONCTIONS PERSONNELLES */
/* MES FONCTIONS PERSONNELLES */

/**
 * Récupère l'id actuel du dresseur "connecté"
 * @rxxxx
 */
function get_collector_by_id($id){
    $file_content = file_get_contents($GLOBALS["prefix_path"]."collector_data.json");
    $json_data = json_decode($file_content, true);
    if (array_key_exists($id, $json_data)){
        $pok = $json_data[$id];
    }
    else {
        $pok = false;
    }
    return $pok;
}

/**
 * Calcule la moyenne de la taille des Pokémon à partir du fichier pokemon_data.json.
 *
 * @return float La moyenne de la taille des Pokémon.
 */
function calculate_average_height() {
    $file_content = file_get_contents($GLOBALS["prefix_path"]."pokemon_data.json");
    $json_data = json_decode($file_content, true);

    $totalHeight = 0;
    $totalPokemon = count($json_data);

    foreach ($json_data as $pokemon) {
        $totalHeight += $pokemon["height"];
    }

    return $totalHeight / $totalPokemon;
}

/**
 * Calcule la moyenne du poids des Pokémon à partir du fichier pokemon_data.json.
 *
 * @return float La moyenne du poids des Pokémon.
 */
function calculate_average_weight() {
    $file_content = file_get_contents($GLOBALS["prefix_path"]."pokemon_data.json");
    $json_data = json_decode($file_content, true);

    $totalWeight = 0;
    $totalPokemon = count($json_data);

    foreach ($json_data as $pokemon) {
        $totalWeight += $pokemon["weight"];
    }

    return $totalWeight / $totalPokemon;
}

/**
 * Sélectionne un Pokémon au hasard à partir du fichier pokemon_data.json.
 *
 * 
 */
function get_random_pokemon() {
    $file_content = file_get_contents($GLOBALS["prefix_path"]."pokemon_data.json");
    $json_data = json_decode($file_content, true);

    return $randomPokemonId = array_rand($json_data);
}

function logout() { // En réalité mise sur compte invité
    $_SESSION['collector_id'] = 4; // L'ID associé au compte invité

    header("Location: index.php?logout=true");
    exit();
}
?>