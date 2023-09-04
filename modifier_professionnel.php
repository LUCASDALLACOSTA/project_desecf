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

$professionnel_id = $_GET['id'];
$sql = "SELECT * FROM professionnel WHERE id_professionnel = $professionnel_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $professionnel = $result->fetch_assoc();
} else {
    echo "Aucun professionnel trouvé avec cet identifiant.";
    exit();
}

// Initialisez la variable $updateSuccess à false
$updateSuccess = false;

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $adresse = $_POST["adresse"];
    $telephone = $_POST["telephone"];
    $mail = $_POST["mail"];
    $statut = $_POST["statut"];
    $duree_stage = $_POST["duree_stage"];
    $date_debut_stage = $_POST["date_debut_stage"];

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
        // Mise à jour des informations du professionnel dans la base de données
        $sql = "UPDATE professionnel SET nom='$nom', prenom='$prenom', adresse='$adresse', telephone='$telephone', mail='$mail', statut='$statut', duree_stage='$duree_stage', date_debut_stage='$date_debut_stage', latitude='$latitude', longitude='$longitude' WHERE id_professionnel=$professionnel_id";

        // Exécutez la requête SQL et définissez $updateSuccess en conséquence
        if ($conn->query($sql) === TRUE) {
            $updateSuccess = true;
        } else {
            $updateSuccess = false;
            echo "Erreur lors de la mise à jour : " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Formulaire de modification du professionnel</title>
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center">
            <img src="assets/img/portfolio/logo.png" alt="Logo" class="logo">
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
    <h1>Formulaire de modification du professionnel</h1>
    
    <!-- Affichez le message de succès ou d'erreur ici -->
    <?php if ($updateSuccess): ?>
        <div style="text-align:center;" class="success-message">
            Mise à jour réussie.
        </div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" value="<?php echo $professionnel['nom']; ?>" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" value="<?php echo $professionnel['prenom']; ?>" required><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" value="<?php echo $professionnel['adresse']; ?>" required><br>

        <label for="telephone">Téléphone :</label>
        <input type="text" name="telephone" value="<?php echo $professionnel['telephone']; ?>" required><br>

        <label for="mail">Mail :</label>
        <input type="email" name="mail" value="<?php echo $professionnel['mail']; ?>" required><br>

        <label for="statut">Statut :</label>
        <select name="statut" required>
            <option value="disponible" <?php if ($professionnel['statut'] == "disponible") echo "selected"; ?>>Disponible</option>
            <option value="non disponible" <?php if ($professionnel['statut'] == "non disponible") echo "selected"; ?>>Non disponible</option>
            <option value="en attente" <?php if ($professionnel['statut'] == "en attente") echo "selected"; ?>>En attente</option>
        </select><br>

        <label for="duree_stage">Durée du stage :</label>
        <input type="text" name="duree_stage" value="<?php echo $professionnel['duree_stage']; ?>" required><br>

        <label for="date_debut_stage">Date de début du stage :</label>
        <input type="date" name="date_debut_stage" value="<?php echo $professionnel['date_debut_stage']; ?>" required><br>

        <input type="submit" value="Enregistrer">
    </form>
</body>

</html>
