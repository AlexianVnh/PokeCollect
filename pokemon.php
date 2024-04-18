<?php
    session_start();
    include 'php-json/pokemon_api.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        logout();
    }

    $targetCollectorId = get_current_id(); // collector ciblé
    $pokemonCollectorData = list_all_pokemon_from_collector($targetCollectorId);

    // Vérifier si une action et un ID sont définis dans l'URL pour les suppressions et additions depuis pokémon détail
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $action = $_GET['action'];
        $pokemonId = $_GET['id'];

        // On vérifie si l'action est "add" ou "delete"
        if ($action === 'add') {
            add_pokemon_to_collector($targetCollectorId, $pokemonId);
        } elseif ($action === 'delete' && isset($_GET['assoc_id'])) {
            $assocId = $_GET['assoc_id'];
            delete_pokemon_from_collector($assocId);

            header("Location: collection.php");
            exit();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Détail - PokéColekt</title>

    <!--Polices en ligne-->
    <link href="https://fonts.cdnfonts.com/css/pokemon-solid" rel="stylesheet"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <!--Polices en ligne-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pixelify+Sans&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">   

    <!--Icons du site-->
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/icons/android-chrome-512x512.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/icons/favicon-32x32.png">
    <link rel="manifest" href="/assets/images/icons/site.webmanifest">
</head>
<body>
    <header> <!--Entête-->
        <section class="header-container">
            <a href="index.php"><img src="assets/images/logo-pokemon.png" width="auto" height="50px" alt="logo" title="Revenir à l'accueil"></a> 
            <nav>
                <ul> <!--Liste des boutons-->
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="catalogue.php">Catalogue</a></li>
                    <li><a href="collection.php">Mes Pokémons</a></li>
                </ul>
            </nav>
            <!--Photo de profile-->
            <div class="profile-picture">
                <form method="post" action="" class="deconnexion2">
                    <button type="submit" name="logout">Déconnexion</button>
                </form>
            </div>
        </section>
    </header>

    <?php
    
        $pokemonId = $_GET['id']; // On récupère l'id du pokémon dans l'URL
        $pokemon = get_pokemon_by_id($pokemonId); // On prend le tableau du Pokémon

        $PokemonCount = 0;
        $encounteredPokemonIds = []; // Tableau pour vérifier qu'on a déjà compté le Pokémon

        foreach ($pokemonCollectorData as $association) { // association = dictionnaire avec assoc_id, collector_id, pokemon_id et pokemon_nickname
            $currentPokemonId = $association['pokemon_id'];
            $currentCollectorId = $association['collector_id'];
            $associationId = $association['association_id'];
                        
            if ($currentPokemonId == $pokemonId && $currentCollectorId == $targetCollectorId && !in_array($associationId, $encounteredPokemonIds)) { // on vérifie qu'il n'est pas dans le tableau
                $PokemonCount++;
                $encounteredPokemonIds[] = $associationId; // On ajoute l'association pour dire qu'on la déjà compté
            }
        }
    ?>
    

    <main class="pokemon-detail-page">
        <section class="left-container">
            <a class="comeback-arrow-container" href="collection.php">
                <span class="comeback-arrow-content"></span>
            </a>
            <section class="pokemon-info">
                <article class="pokemon-name-and-type">
                    <?php
                    if (isset($_GET['surname'])) {
                        $currentPokemonName = $_GET['surname']; // On prend le surnom donné dans l'URL
                    }
                    else {
                        $currentPokemonName = ucfirst($pokemon["identifier"]);
                    }
                    $originalPokemonName = $currentPokemonName;

                    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['submitNameChange'])) {
                        $newPokemonName = $_GET['newPokemonName'];
                        $pokemonId = $_GET['id'];
                        $associationId = $_GET['assoc_id'];

                        // On change le nom du Pokémon
                        change_pokemon_nickname($associationId, $newPokemonName);

                        // On met à jour le nom actuel du Pokémon
                        $currentPokemonName = ucfirst($newPokemonName);
                    }

                    // On utilise le nom actuel ou le nom original selon le cas
                    $pokemonName = isset($currentPokemonName) ? $currentPokemonName : $originalPokemonName;
                    ?>

                    <div class="title-rename pokemon-rename">
                        <form method="get" action="">
                            <?php if (isset($_GET['editName'])) : ?>
                                <input type="text" name="newPokemonName" class="edit-pokemon-name" value="<?= $pokemonName; ?>">
                                <input type="hidden" name="id" value="<?= $_GET['id']; ?>">
                                <input type="hidden" name="assoc_id" value="<?= $_GET['assoc_id']; ?>">
                                <input type="submit" name="submitNameChange" class="edit-pokemon-name-validation" value="Enregistrer">
                            <?php else : ?>
                                <h2><?= $pokemonName; ?></h2>
                                <?php if (isset($_GET['assoc_id'])) : ?>
                                    <a href="?editName=true&id=<?= $_GET['id']; ?>&assoc_id=<?= $_GET['assoc_id']; ?>">Modifier le nom</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </form>
                    </div>


                    <div class="type-images">
                        <?php foreach ($pokemon["types"] as $type): ?>
                            <img src="assets/images/types/<?=$type; ?>.png" height="65px" width="auto" alt="Type <?= $type; ?>" title="Type <?= $type; ?>">
                        <?php endforeach; ?>
                    </div>
                </article>


                <!-- Boutons ajouter et supprimer + compteur -->
                <article class="add-and-delete">
                    <a href="collection.php?action=add&id=<?= $pokemonId; ?>" class="p-button add-button">
                        <span class="plus1"></span>
                        <span class="plus2"></span>
                    </a>
                    <div class="pokemon-count-container">
                        <p>Nombre de fois que vous avez ce pokémon :</p>
                        <h2 class="pokemon-count" id="pokemonCount"><?= $PokemonCount;?></h2>
                    </div>
                    <?php
                        if (isset($_GET['assoc_id'])) { // Si on un association id (obligatoire pour pouvoir supprimer) on affiche le bouton delete
                            $assocId = $_GET['assoc_id'];
                            echo '
                            <a href="pokemon.php?action=delete&id='.$pokemonId.'&assoc_id='.$assocId.'" class="p-button delete-button">
                                <span class="plus1"></span>
                            </a> ';
                        } // si on en a pas on ne fait rien et donc on l'affiche pas pour pas de bug
                    ?>
                </article>


                <section class="bottom-left-container">
                    <section class="weight-height-stats-container">
                        <div class="weight-height-stats-container-left">    
                            <article class="column height-your-pokemon">
                                <p class="bold margin-b-5"><?= ($pokemon["weight"]);?> cm</p>
                                <div class="your-height-content"></div>
                                <p>Taille de votre Pokémon</p>
                            </article>
                        </div>
    
                        <div class="weight-height-stats-container-right">
                            <article class="column weight-your-pokemon">
                                <p class="bold margin-b-5"><?= ($pokemon["height"]);?> Kgs</p>
                                <div class="your-weight-content"></div>
                                <p>Poids de votre Pokémon</p>
                            </article>
                        </div>
                    </section>
                    <section class="other-pokemon">
                        <h2>Autres Pokémons</h2>
                        <ul class="pokemon-list">
                            <?php                            
                                // Boucle pour obtenir cinq Pokémon choisis au hasard
                                for ($i = 0; $i < 5; $i++) {
                                    $randomPokemonId = get_random_pokemon();
                                    echo '<li><a href="pokemon.php?id='.$randomPokemonId.'"><img src="assets/images/pokemon_img/96px/'.$randomPokemonId.'.png" width="92px" height="92px" alt=""></a></li>';
                                }
                            ?>
                        </ul>
                    </section>
                </section>
            </section>
        </section>
        <section class="right-container">
            <!--image de fond en CSS-->            
            <img src="assets/images/pokemon_img/96px/<?php echo($pokemonId);?>.png" width="400" height="400" alt="image du pokémon">
            <h1 class="pokemon-name"><?= $pokemonName;?> #<?php echo($pokemonId);?></h1>
            <p class="bold pokemon-base-experience">Expérience de base  <span><?php echo($pokemon["base_experience"]);?></span></p>
        </section>
    </main>
</body>
</html>