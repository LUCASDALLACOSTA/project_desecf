<?php
// Inclure la bibliothèque league/csv
require 'vendor/autoload.php';

use League\Csv\Reader;

// Inclure le fichier de connexion à la base de données
include 'includes/connexion_bdd.php';

// Vérifier si un fichier a été uploadé
if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    // Récupérer le chemin temporaire du fichier
    $tmpFilePath = $_FILES['csv_file']['tmp_name'];

    // Créer un objet Reader pour le fichier CSV
    $csv = Reader::createFromPath($tmpFilePath, 'r');
    $csv->setHeaderOffset(0); // Spécifier que la première ligne contient les en-têtes

    // Parcourir les lignes du fichier CSV
    foreach ($csv as $row) {
        // Récupérer les valeurs des colonnes du fichier CSV
        $nom = $row['nom'];
        $prenom = $row['prenom'];
        $adresse1 = $row['Adresse 1'];
        $codePostal = $row['Code Postal'];
        $ville = $row['Ville'];
        $indicatif = $row['Indicatif'];
        $telephone = $row['Tél. (SMS)'];
        $mail = $row['Adresse E-mail'];

        // Construire l'adresse avec le code postal et la ville
        $adresseComplete = $adresse1 . ', ' . $codePostal . ' ' . $ville;

        // Convertir l'adresse en latitude et longitude
        $geocodeUrl = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($adresseComplete) . '&format=json&limit=1';

        // Effectuer la requête vers l'API de géocodage
        $geocodeResponse = file_get_contents($geocodeUrl);

        // Vérifier si la requête a réussi
        if ($geocodeResponse !== false) {
            $geocodeData = json_decode($geocodeResponse, true);

            // Vérifier si des résultats ont été trouvés
            if (!empty($geocodeData) && isset($geocodeData[0]['lat']) && isset($geocodeData[0]['lon'])) {
                $latitude = $geocodeData[0]['lat'];
                $longitude = $geocodeData[0]['lon'];

                // Insérer les données dans la base de données
                $query = "INSERT INTO professionel (nom, prenom, adresse, lattitude, longitude, telephone, mail) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $bdd->prepare($query);
                $stmt->execute([$nom, $prenom, $adresseComplete, $latitude, $longitude, $indicatif . ' ' . $telephone, $email]);
            }
        }
    }
    
    header("Location: carte.php");
    exit();
}
?>
