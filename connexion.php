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

    form {
        margin: 20px auto;
        max-width: 400px;
        padding: 20px;
        border: 1px solid #ccc;
    }

    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }

    input,
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    input[type="submit"] {
        background-color: #333;
        color: #fff;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #ffd700;
    }

    .logo {
        width: 50px;
        height: auto;
        margin-right: 10px;
    }

    .success-message {
        color: green;
    }

    .error-message {
        color: red;
    }

    .scrollable-content {
        max-height: 900px;
        overflow-y: auto;
    }
</style>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Connexion - PROJET DECESF</title>
    <meta content="PROJET DECESF" name="description">

    <?php
    include 'includes/connexion_bdd.php'; // Connexion à la base de données
    session_start(); // Démarrage de la session

    if (isset($_GET['disconnect']) && !isset($_SESSION['Type_user'])) {
        $message_warning = "Vous avez été déconnecté !";
    }
    if (isset($_GET['information']) && !isset($_SESSION['Type_user'])) {
        $message_information = "Votre code confidentiel a bien été changé !";
    }
    if (isset($_GET['error']) && !isset($_SESSION['Type_user'])) {
        if ($_GET['error'] == 1) {
            $message_error = "Vous devez être connecté pour accéder à cette page !";
        } else {
            $message_error = "Vous n'avez pas accès à cette page !";
        }
    }

    // Vérifiez si l'utilisateur est connecté
    $connected = isset($_SESSION['connected']) && $_SESSION['connected'] === true;
    ?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <!-- Mettez ici vos balises meta, titre, etc. -->

        <?php
        if (isset($_POST['submit'])) {
            $mailconnect = htmlspecialchars($_POST["username"]);
            $mdpconnect = htmlspecialchars($_POST["password"]);

            if (!empty($mailconnect) && !empty($mdpconnect)) {
                $req_verif_existe_user = $dbh->prepare("SELECT * FROM user WHERE username = ?");
                $req_verif_existe_user->execute(array($mailconnect));
                $resultat_user = $req_verif_existe_user->fetch();
                $resultat_verif_existe_user = $req_verif_existe_user->rowCount();

                if ($resultat_verif_existe_user > 0) {
                    $isPasswordCorrect = password_verify($mdpconnect, $resultat_user['password']);

                    if ($isPasswordCorrect == 1) {
                        $_SESSION['id'] = $resultat_user['id'];
                        $_SESSION['username'] = $resultat_user['username'];
                        $_SESSION['message_bienvenue'] = "Bonjour, " . $_SESSION['username'] . " !";
                        $_SESSION['connected'] = true; // Définissez la session comme connectée
                        header('Location: index.php');
                    } else {
                        $erreur = "Nom d'utilisateur ou mot de passe incorrect";
                    }
                }
            }
        }

        if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
            $message_bienvenue = "Bonjour, " . $_SESSION['username'] . " !";
        }
        ?>
    </head>

<body>
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <img src="assets/img/logo.png" alt="Logo" class="logo">
            <ul>
                <?php
                echo '<li><a href="./"></span>&nbsp;Accueil</a></li>';
                echo '<li><a href="connexion.php"><span></span>&nbsp;Connexion</a></li>';
                if ($connected) { // Affiche les liens si l'utilisateur est connecté
                    echo '<li><a href="carte.php">&nbsp;Carte</a></li>';
                    echo '<li><a href="ajout_csv.php">&nbsp;Ajout via csv</a></li>';
                    echo '<li><a href="ajout_professionnel.php">&nbsp;Ajouter un professionnel</a></li>';
                    echo '<li><a href="deconnexion.php">&nbsp;Déconnexion</a></li>';
                }
                ?>
            </ul>
        </div>
    </header>
    <div class="container">
        <h2>Connexion</h2>
        <?php
        if (isset($erreur)) {
            echo '<div class="alert alert-danger">' . $erreur . '</div>';
        }
        ?>

        <form method="POST" action="connexion.php" style="background-color: white;">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary" name="submit">Se connecter</button>
        </form>
    </div>
</body>

</html>