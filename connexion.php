<?php
include 'includes/connexion_bdd.php'; //connexion bdd


if (isset($_GET['disconnect']) && !empty($_GET['disconnect']) && !isset($_SESSION['Type_user'])) {
    $message_warning = "Vous avez été déconnecté !";
}
if (isset($_GET['information']) && !empty($_GET['information']) && !isset($_SESSION['Type_user'])) {
    $message_information = "Votre code confidentiel à bien été changé !";
}
if (isset($_GET['error']) && !empty($_GET['error']) && !isset($_SESSION['Type_user'])) {
    if ($_GET['error'] == 1) {
        $message_error = "Vous devez être connecté pour accéder à cette page !";
    } else {
        $message_error = "Vous n'avez pas accès à cette page !";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<style>
    /* Reset CSS */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Global Styles */
    body {
        font-family: Arial, sans-serif;
        background-image: url("assets/img/fondAccueil.png");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: top;
        background-color: #f8f8f8;
    }

    .container {
        width: 100%;
        max-width: 960px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header Styles */
    #header {
        background-color: #333;
        color: #fff;
        padding: 10px 0;
    }

    #header .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #header ul {
        list-style: none;
        display: flex;
    }

    #header ul li {
        margin-right: 10px;
    }

    #header ul li a {
        color: #fff;
        text-decoration: none;
        padding: 5px;
    }

    #header ul li a:hover {
        color: #ffd700;
    }

    /* Content Styles */
    h1,
    h2 {
        text-align: center;
        margin: 20px 0;
        background-color: #f8f8f8;
    }

    .logo {
        width: 50px;
        height: auto;
        margin-right: 10px;
    }

    .footer-banner {
        max-height: 100px;
        /* Hauteur maximale */
        text-align: center;
        /* Centrer le texte */
        width: 100%;
        /* Largeur de 100% pour s'adapter à la largeur de l'écran */
        background-color: #f8f8f8;
        /* Couleur de fond */
        padding: 10px 0;
        /* Espacement interne */
    }
</style>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Connexion - PROJET DECESF</title>
    <meta content="PROJET DECESF" name="description">

</head>

<body>
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <img src="assets/img/logo.png" alt="Logo" class="logo">
            <ul>
                <li><a href="./"></span>&nbsp;Accueil</a></li>
                <li><a href="connexion.php"><span></span>&nbsp;Connexion</a></li>
                <li><a href="ajout_csv.php">&nbsp;Ajout via csv</a></li>
                <li><a href="ajout_professionnel.php">&nbsp;Ajouter un professionnel</a></li>
            </ul>
        </div>
</body>