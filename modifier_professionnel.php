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
    $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
    $prenom = mysqli_real_escape_string($conn, $_POST["prenom"]);
    $adresse = mysqli_real_escape_string($conn, $_POST["adresse"]);
    $telephone = mysqli_real_escape_string($conn, $_POST["telephone"]);
    $mail = mysqli_real_escape_string($conn, $_POST["mail"]);
    $statut = mysqli_real_escape_string($conn, $_POST["statut"]);
    $duree_stage = mysqli_real_escape_string($conn, $_POST["duree_stage"]);
    $date_debut_stage = mysqli_real_escape_string($conn, $_POST["date_debut_stage"]);
    $nom_structure = mysqli_real_escape_string($conn, $_POST["nom_structure"]);
    $type_structure = mysqli_real_escape_string($conn, $_POST["type_structure"]);
    $statut_stage_2 = mysqli_real_escape_string($conn, $_POST["statut_stage_2"]);
    $duree_stage_2 = mysqli_real_escape_string($conn, $_POST["duree_stage_2"]);
    $date_debut_stage_2 = mysqli_real_escape_string($conn, $_POST["date_debut_stage_2"]);

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
        $sql = "UPDATE professionnel 
        SET 
            nom='$nom', 
            prenom='$prenom', 
            adresse='$adresse', 
            telephone='$telephone', 
            mail='$mail', 
            statut='$statut', 
            duree_stage='$duree_stage', 
            date_debut_stage='$date_debut_stage', 
            nom_structure='$nom_structure', 
            type_structure='$type_structure',
            statut_stage_2='$statut_stage_2', 
            duree_stage_2='$duree_stage_2', 
            date_debut_stage_2='$date_debut_stage_2',
            latitude='$latitude', 
            longitude='$longitude'
        WHERE 
            id_professionnel=$professionnel_id";


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
            <img src="assets/img/logo.png" alt="Logo" class="logo">
            <ul>
                <ul>
                    <?php
                    echo '<li><a href="./"></span>&nbsp;Accueil</a></li>';
                    echo '<li><a href="connexion.php"><span></span>&nbsp;Connexion</a></li>';
                    if (isset($_SESSION['connected']) === true) {
                        echo '<li><a href="carte.php">&nbsp;Carte</a></li>';
                        echo '<li><a href="ajout_csv.php">&nbsp;Ajout via csv</a></li>';
                        echo '<li><a href="ajout_professionnel.php">&nbsp;Ajouter un professionnel</a></li>';
                        echo '<li><a href="deconnexion.php">&nbsp;Déconnexion</a></li>';
                    }
                    ?>
                </ul>
            </ul>
        </div>
    </header>
</head>

<body>
    <div class="scrollable-content">
        <h1>Formulaire de modification du professionnel</h1>

        <!-- Affichez le message de succès ou d'erreur ici -->
        <?php if ($updateSuccess) : ?>
            <div style="text-align:center;" class="success-message">
                Mise à jour réussie.
            </div>
        <?php endif; ?>

        <form method="POST" action="">

            <label for="nom_structure">Nom de la structure :</label>
            <input type="text" name="nom_structure" value="<?php echo $professionnel['nom_structure']; ?>" required><br>

            <label for="type_structure">Type de structure :</label>
            <input type="text" name="type_structure" value="<?php echo $professionnel['type_structure']; ?>"><br>

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

            <h2 style="text-align:center;"> Premier stage: </h2>

            <label for="statut">Statut:</label>
            <select name="statut" required>
                <option value="disponible" <?php if ($professionnel['statut'] == "disponible") echo "selected"; ?>>Disponible</option>
                <option value="non disponible" <?php if ($professionnel['statut'] == "non disponible") echo "selected"; ?>>Non disponible</option>
                <option value="en attente" <?php if ($professionnel['statut'] == "en attente") echo "selected"; ?>>En attente</option>
            </select><br>

            <label for="duree_stage">Durée du stage:</label>
            <input type="text" name="duree_stage" value="<?php echo $professionnel['duree_stage']; ?>" required><br>

            <label for="date_debut_stage">Date de début du stage:</label>
            <input type="date" name="date_debut_stage" value="<?php echo $professionnel['date_debut_stage']; ?>" required><br>

            <h2 style="text-align:center;"> Deuxième stage: </h2>

            <label for="statut_stage_2">Statut:</label>
            <select name="statut_stage_2" required>
                <option value="disponible" <?php if ($professionnel['statut_stage_2'] == "disponible") echo "selected"; ?>>Disponible</option>
                <option value="non disponible" <?php if ($professionnel['statut_stage_2'] == "non disponible") echo "selected"; ?>>Non disponible</option>
                <option value="en attente" <?php if ($professionnel['statut_stage_2'] == "en attente") echo "selected"; ?>>En attente</option>
            </select><br>

            <label for="duree_stage_2">Durée du stage:</label>
            <input type="text" name="duree_stage_2" value="<?php echo $professionnel['duree_stage_2']; ?>"><br>

            <label for="date_debut_stage_2">Date de début du stage:</label>
            <input type="date" name="date_debut_stage_2" value="<?php echo $professionnel['date_debut_stage_2']; ?>"><br>

            <input type="submit" value="Enregistrer">
        </form>
    </div>
</body>

</html>