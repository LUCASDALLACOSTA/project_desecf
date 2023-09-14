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

    h3 {
        margin-top: 40px;
    }

    form {
        margin-top: 20px;
    }

    form input[type="file"] {
        margin-bottom: 10px;
    }

    form input[type="submit"] {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    form input[type="submit"]:hover {
        background-color: #0056b3;
    }
    
</style>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>PROJET DESECF</title>
    <meta content="PROJET DESECF" name="description">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <img src="assets/img/logo.png" alt="Logo" class="logo">
            <ul>
                <li><a href="./"></span>&nbsp;Accueil</a></li>
                <li><a href="connexion.php"><span></span>&nbsp;Connexion</a></li>
                <li><a href="carte.php">&nbsp;Carte</a></li>
                <li><a href="ajout_professionnel.php">&nbsp;Ajouter un professionnel</a></li>
            </ul>
        </div>
    </header>
    <!-- End Header -->
    <h1>Ajouter des données via CSV</h2>
        <h2>
        <?php
        // Récupérer le message depuis l'URL
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            if (!empty($message)) {
                // Déterminer la classe CSS en fonction de $insertResult
                $messageClass = isset($insertResult) && $insertResult ? 'success-message' : 'error-message';
                
                // Afficher le message avec la classe CSS appropriée
                echo '<div class="' . $messageClass . '">' . htmlspecialchars($message) . '</div>';
            }
        }
        ?>
            <form action="traitement_csv.php" method="post" enctype="multipart/form-data">
                <input type="file" name="csv_file" required>
                <input type="submit" value="Importer">
            </form>
        </h2>
</body>

</html>