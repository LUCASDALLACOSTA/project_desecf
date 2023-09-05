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
        overflow: hidden;
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

<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "desecf";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$ajoutSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Vérifie si le formulaire a été soumis

    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $mail = $_POST['mail'];
    $statut = $_POST['statut'];
    $duree_stage = $_POST['duree_stage'];
    $date_debut_stage = $_POST['date_debut_stage'];
    $nom_structure = $_POST['nom_structure'];
    $type_structure = $_POST['type_structure'];

    // Géocodage de l'adresse pour obtenir les coordonnées de latitude et longitude
    $addressEncoded = urlencode($adresse);
    $geocodeUrl = "https://nominatim.openstreetmap.org/search?format=json&q=" . $addressEncoded;
    $opts = [
        'http' => [
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $geocodeResponse = file_get_contents($geocodeUrl, false, $context);
    $geocodeData = json_decode($geocodeResponse, true);

    if (!empty($geocodeData) && isset($geocodeData[0]['lat']) && isset($geocodeData[0]['lon'])) {
        $latitude = $geocodeData[0]['lat'];
        $longitude = $geocodeData[0]['lon'];

        // Insérer les données dans la base de données
        $sql = "INSERT INTO professionnel (nom, prenom, adresse, latitude, longitude, telephone, mail, statut, duree_stage, date_debut_stage, nom_structure, type_structure) VALUES ('$nom', '$prenom', '$adresse', '$latitude', '$longitude', '$telephone', '$mail', '$statut', '$duree_stage', '$date_debut_stage', '$nom_structure', '$type_structure')";

        if ($conn->query($sql) === TRUE) {
            $ajoutSuccess = true;
        } else {
            $ajoutSuccess = false;
            echo "L'ajout n'a pas réussi : " . $conn->error;
        }
    } else {
        echo "Erreur de géocodage de l'adresse.";
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un professionnel</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center">
            <img src="assets/img/logo.png" alt="Logo" class="logo">
            <ul>
                <li><a href="./">&nbsp;Accueil</a></li>
                <li><a href="connexion.php">&nbsp;Connexion</a></li>
                <li><a href="carte.php">&nbsp;Carte</a></li>
                <li><a href="ajout_csv.php">&nbsp;Ajout via csv</a></li>
            </ul>
        </div>
    </header>
</head>
<body>
<div class="scrollable-content">
    <h1>Ajouter un professionnel</h1>

    <?php if ($ajoutSuccess): ?>
        <div style="text-align:center;" class="success-message">
           Ajout d'un professionnel réussi
        </div>
    <?php endif; ?>

    <form method="post" action="ajout_professionnel.php">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required><br>

        <label for="telephone">Téléphone :</label>
        <input type="text" name="telephone" required><br>

        <label for="mail">Email :</label>
        <input type="email" name="mail" required><br>

        <label for="statut">Statut :</label>
        <select name="statut" required>
            <option value="disponible">Disponible</option>
            <option value="non disponible">Non disponible</option>
            <option value="en attente">En attente</option>
        </select><br>

        <label for="duree_stage">Durée du stage :</label>
        <input type="text" name="duree_stage"><br>

        <label for="date_debut_stage">Date de début du stage :</label>
        <input type="date" name="date_debut_stage"><br>

        <label for="nom_structure">Nom de la structure :</label>
        <input type="text" name="nom_structure" required><br>

        <label for="type_structure">Type de structure :</label>
        <input type="text" name="type_structure"><br>

        <input type="submit" value="Ajouter">
    </form>
    </div>
</body>
</html>
