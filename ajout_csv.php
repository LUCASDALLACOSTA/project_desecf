<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>PROJET DESECF</title>
    <meta content="PROJET DESECF" name="description">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center">
            <ul>
                <li><a href="./">&nbsp;Accueil</a></li>
                <li><a href="connexion.php">&nbsp;Connexion</a></li>
                <li><a href="carte.php">&nbsp;Carte</a></li>
                <li><a href="ajout_csv.php">&nbsp;Ajout via csv</a></li>
            </ul>
        </div>
    </header>
    <!-- End Header -->
    <h3>Ajouter des données via CSV</h3>

    <form action="traitement_csv.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" required>
        <input type="submit" value="Importer">
    </form>
</body>

</html>
