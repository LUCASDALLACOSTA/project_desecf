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

// Récupération des informations du professionnel à partir de la base de données
$professionnel_id = $_GET['id']; // Supposons que l'ID du professionnel soit passé en paramètre dans l'URL
$sql = "SELECT * FROM professionnel WHERE id_professionnel = $professionnel_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $professionnel = $result->fetch_assoc();
} else {
    echo "Aucun professionnel trouvé avec cet identifiant.";
    exit();
}

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

    // Mise à jour des informations du professionnel dans la base de données
    $sql = "UPDATE professionnel SET nom='$nom', prenom='$prenom', adresse='$adresse', telephone='$telephone', mail='$mail', statut='$statut', duree_stage='$duree_stage', date_debut_stage='$date_debut_stage' WHERE id_professionnel=$professionnel_id";

    if ($conn->query($sql) === TRUE) {
        echo "Les informations ont été mises à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour des informations : " . $conn->error;
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Formulaire de modification du professionnel</title>
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
</head>

<body>
    <h1>Formulaire de modification du professionnel</h1>
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
        <input type="mail" name="mail" value="<?php echo $professionnel['mail']; ?>" required><br>

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