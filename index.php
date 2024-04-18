<?php
    session_start();
    include 'php-json/pokemon_api.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        logout();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Accueil - PokéColekt</title>
    
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

    <!--Scripts javascript-->
    <script src="javascript/script.js"></script>
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


    <main> <!--Contenu de la page-->
        <section class="welcome-container"> <!--Hero section de la page-->
            <section class="welcome-content">
                <section class="welcome-text">
                    <h1>Bienvenue !!</h1>
                    <?php 
                        function register($login, $collectorId, $gender) {
                            try {
                                add_collector($collectorId, $login, $gender);
                                $_SESSION['collector_id'] = $collectorId;
                                echo '<p class="feedback success">Bien joué ! Vous êtes connecté.</p>';
                            } catch (ErrorException $e) { //erreur
                                echo '<p class="feedback error">Id déjà existant ! Essayez à nouveau</p>';
                            }  
                        }
                        function login($login, $collectorId) {
                            $collector = get_collector_by_id($collectorId);
                            if ($collector != false) {
                                if ($collector["collector_name"] == $login) {
                                    $_SESSION['collector_id'] = $collectorId;
                                    echo '<p class="feedback success">Bien joué ! Vous êtes connecté.</p>';
                                }
                                else {
                                    echo '<p class="feedback error">Nom incorrect ! Essayez à nouveau</p>';
                                }
                            }
                            else {
                               echo '<p class="feedback error">Id incorrect ! Essayez à nouveau</p>';
                            }
                            
                        }

                        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register']) && isset($_POST['collectorId']) && isset($_POST['gender'])) {
                            register($_POST['register'], $_POST['collectorId'], $_POST['gender']);
                        }
                        else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']) && isset($_POST['collectorId']) && isset($_POST['gender'])) {
                            login($_POST['login'], $_POST['collectorId']);
                        }

                        $file_content = file_get_contents($GLOBALS["prefix_path"]."collector_data.json");
                        $collector_data = json_decode($file_content, true);
                        $currentId = get_current_id(); 
                    ?>
                    <h2>Un <?= $collector_data[$currentId]["collector_name"];?> sauvage est apparu !</h2>
                </section>
                <section class="welcome-login">
                    <div class="top-content">
                        <div class="top login"><h2>Log In</h2></div>
                        <div class="top signin"><h2>Sign In</h2></div>
                    </div>

                    <form method="post" class="login-content">
                        <div>
                            <input class="login-input" type="text" id="login" name="login" placeholder="Nom d'utilisateur : Invite">
                        </div>
                        <div>
                            <input class="login-input" type="text" id="collectorId" name="collectorId" placeholder="ID : 4">
                        </div>
                        <div class="login-content-optional">
                            <input class="login-input" type="text" id="gender" name="gender" placeholder="Genre">
                        </div>
            
                        <input type="submit" value="Se connecter" id="submitBtn" class="title-size">
                    </form>
                    
                    

                </section>
            </section>
        </section>

        <section class="catalogue-and-collection"> <!--Boutons pokéball qui s'ouvrent-->
            <div class="pokeball-container-title">
                <h2>Catalogue</h2>
                <h2>Collection</h2>
            </div>
            <section class="pokeball-container">
                <article class="pokeball-left">
                    <img src="assets/images/top_catalogue_ball.svg" width="360" alt="Pokéball menant vers le catalogue" class="pokeball-img" ondragstart="return false;">
                    <div class="pokeball-left-content">
                        <div class="pokeball-info">
                            <p>Parcourez le catalogue des Pokémons disponibles.</p>
                            <a class="pokeball-button" href="catalogue.php">Découvrir ></a>
                        </div>
                    </div>
                    <img src="assets/images/bottom_catalogue_ball.svg" width="344" alt="Pokéball menant vers le catalogue" class="pokeball-img bottom-ball-1" ondragstart="return false;">
                </article>
    
                <article class="pokeball-right">
                    <img src="assets/images/top_collection_ball.svg" width="348" alt="Pokéball menant vers la collection" class="pokeball-img" ondragstart="return false;">
                    <div class="pokeball-right-content">
                        <div class="pokeball-info">
                            <p>Explorez votre collection et vos Pokémons.</p>
                            <a class="pokeball-button" href="collection.php">Voir ></a>
                        </div>
                    </div>
                    <img src="assets/images/bottom_collection_ball.svg" width="348" alt="Pokéball menant vers la collection" class="pokeball-img bottom-ball-2" ondragstart="return false;">
                </article>
            </section>
        </section>

        

            <section class="pokemon-caroussel">
                <div class="pokemon-slides">
                    <?php
                        $selectedPokemons = [];
                    
                        // Boucle pour obtenir cinq Pokémon choisis au hasard
                        for ($i = 0; $i < 7; $i++) {
                            $randomPokemonId = get_random_pokemon();
                            $selectedPokemons[] = $randomPokemonId;
                        }
                        for ($j = 0; $j < 3; $j++) {
                            // On affiche les images des cinq Pokémon sélectionnés
                            foreach ($selectedPokemons as $pokemonId) {
                                echo '<img src="assets/images/pokemon_img/96px/' . $pokemonId . '.png" alt="" title="">';
                            }
                        }
                    ?>
                </div>
            </section>

        <section class="blabla"> <!--Contenu texte additionnel-->
            <section class="blabla-top">
                <h1 class="title">Découvrez le catalogue des pokémons disponibles et construisez votre collection ici !</h1>
                <h2 class="bold">
                    Pokémon, une contraction de "Pocket Monsters" en japonais, est une 
                    franchise de médias créée par Nintendo.
                </h2>
                <article>
                    <p>
                        Elle a été introduite pour la première fois par Nintendo en 1996. L'un des aspects les plus populaires de Pokémon est la série de jeux vidéo, où les joueurs incarnent des dresseurs Pokémon qui capturent et dressent des créatures appelées Pokémon. L'objectif principal est souvent de devenir le Maître Pokémon en remportant des combats contre d'autres dresseurs et en collectant des badges de différents gymnases.
                        Chaque Pokémon possède ses propres caractéristiques, types et capacités uniques.
                        Les types incluent des éléments tels que l'eau, le feu, l'herbe, etc.
                    </p>
                    <p class="margin-50">
                        Les combats Pokémon se déroulent au tour par tour, où les dresseurs choisissent les actions de leurs Pokémon, telles que des attaques, des changements de Pokémon et l'utilisation d'objets.
                        La franchise Pokémon englobe également une série animée, des jeux de cartes à collectionner, des films, des jouets, des vêtements et d'autres produits dérivés. L'idée centrale est l'aventure et l'amitié, mettant en avant la collaboration entre les dresseurs et leurs Pokémon pour atteindre leurs objectifs respectifs.
                    </p>
                </article>    
            </section>
            <section class="blabla-bottom">
                <h2>Les nouveautés PokémonGo</h2>
                <div class="articles">
                    <article class="article-item article1">
                        <div class="article-background a-b-i-1"></div>
                        <p class="bold">Pokémon Go version Pixel</p>
                        <p>
                            Pokémon go prévoit de rajouter des versions des
                            créatures actuellement présentes versions Pixel.
                            Ces Pokémons seraient plus rares et plus forts !
                        </p>
                    </article>
                    <article class="article-item article2">
                        <div class="article-background a-b-i-2"></div>
                        <img src="assets/images/QR_code.jpg" width="110px" alt="QR code du dresseur" title="Scannez ce QR code">
                        <p class="bold">Le meilleur dresseur 2023 dévoilé</p>
                        <p>
                            Scannez le QR code afin d'ajouter en amitié
                            le meilleur dresseur de 2023.
                        </p>
                    </article>
                    <article class="article-item article3">
                        <div class="article-background a-b-i-3"></div>
                        <p class="bold">De nouveaux Pokémons</p>
                        <p>
                            De nouveaux Pokémons sont arrivés !
                            Poussacha, Chochodile, Coiffeton, Gourmelet, 
                            Matourgeon, Crocogril et bien d'autres peuvent 
                            maintenant être capturés
                        </p>
                    </article>
                </div>    
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
</html>