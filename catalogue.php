<?php
    session_start();
    include 'php-json/pokemon_api.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        logout();
    }

    // Vérifier si une action et un ID sont définis dans l'URL pour les suppressions et additions depuis pokémon détail
    if (isset($_GET['action']) && isset($_GET['id'])) {
        $action = $_GET['action'];
        $pokemonId = $_GET['id'];

        // On vérifie si l'action est "add" ou "delete"
        if ($action === 'add') {
            add_pokemon_to_collector($targetCollectorId, $pokemonId);

            header("Location: collection.php");
            exit();
        } elseif ($action === 'delete' && isset($_GET['assoc_id'])) {
            $assocId = $_GET['assoc_id'];
            delete_pokemon_from_collector($assocId);

            header("Location: collection.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Catalogue - PokéColekt</title>

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

    <main>
        <section class="catalogue-list">
            <h1 class="title">Catalogue des Pokémons disponibles</h1>
            <p>Ajouter ou supprimez les Pokémons de votre collection avec les boutons plus et moins.</p>
            
            <section class="pokemon-catalogue-slides-container">
                <?php
                $targetCollectorId = get_current_id(); // collector ciblé
                $pokemonCollectorData = list_all_pokemon_from_collector($targetCollectorId);
                $pokemonData = list_all_pokemon();
                

                // Parcourir le tableau associatif et afficher les détails de chaque Pokémon
                foreach ($pokemonData as $pokemonId => $pokemon):?>
                    <a href="pokemon.php?id=<?=$pokemonId;?>">
                        <article class="pokemon-catalogue-card">
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512" title="Voir le détail"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
                            <h1 class="background-numero"> <?= str_pad($pokemonId, 3, '0', STR_PAD_LEFT)?> </h1> <!--*3 = nombre de caractère voulu, 0 = ce avec quoi on comble et par la-->
                            <img src="assets/images/pokemon_img/96px/<?= ($pokemonId);?>.png" width="190px" height="190px" alt="<?php $pokemon['identifier'] ?>" title="<?php $pokemon['identifier']?>">
                            <h2> <?=ucfirst($pokemon['identifier'])?></h2> <!--Echo-->
                            <div class="card-add-delete-container">
                                <a href="collection.php?action=add&id=<?= $pokemonId; ?>" class="p-button add-button">
                                    <span class="plus1"></span>
                                    <span class="plus2"></span>
                                </a>
                            </div>
                        </article>
                    </a>
                <?php 
                endforeach; //Syntaxe pour dire qu'on a bien fini le foreach
                ?>
            </section>
        </section>
    </main>
    <footer> <!--Footer de la page-->
        <section class="footer-content">
            <ul>
                <li class="li-title">Mes informations</li>
                <li><a href="https://iut-lannion.univ-rennes.fr/metiers-du-multimedia-et-de-linternet">IUT de Lannion</a></li>
                <ul class="ul-link">
                    <li>
                        <a target="_blank" href="https://www.linkedin.com/in/alexian-vannieuwenhuyze-a8583526a/">
                            <svg xmlns="http://www.w3.org/2000/svg" height="44" width="40" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>  
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="mailto:vnh.alexian@gmail.com">                    
                            <svg xmlns="http://www.w3.org/2000/svg" height="50" width="50" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>                
                        </a>
                    </li>
                </ul>
            </ul>
            <ul>
                <li class="li-title">Mon Site</li>
                <li>Dans le cadre de la SAE105</li>
                <li>Développé durant Janvier 2024</li>
                <li>Langages :
                    <ul class="langages-detail">
                        <li>HTML</li>
                        <li>CSS</li>
                        <li>Javascript</li>
                        <li>PHP</li>
                    </ul>
                </li>
            </ul>
            <ul>
                <li class="li-title">Nos services</li>
                <li><a href="catalogue.php">Naviguez à travers le catalogue</a></li>
                <li><a href="catalogue.php">Créez votre collection</a></li>
                <li><a href="catalogue.php">Ajouter des Pokémons</a></li>
                <li><a href="collection.php">Supprimer des Pokémons</a></li>
                <li><a href="collection.php">Renommer des Pokémons</a></li>
            </ul>
            <ul>
                <li class="li-title">Informations sur les Pokémons</li>
                <li>Type(s)</li>
                <li>Numéro</li>
                <li>Taille</li>
                <li>Poids</li>
                <li>Expérience</li>
            </ul>
        </section>
    </footer>
</body> 
<!--Comment faire pour que quand on refresh on reste au même endroit