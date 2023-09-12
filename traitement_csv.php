<?php
require 'vendor/autoload.php';

use League\Csv\Reader;

// Fonction pour supprimer les doublons de noms de colonnes
function removeDuplicateHeaders($csvFilePath)
{
    $csv = file_get_contents($csvFilePath);
    $lines = explode("\n", $csv);
    $headers = explode(",", $lines[0]);
    $uniqueHeaders = array_unique($headers);
    $lines[0] = implode(",", $uniqueHeaders);
    file_put_contents($csvFilePath, implode("\n", $lines));
}

// Chemin temporaire du fichier CSV
$tmpFilePath = $_FILES['csv_file']['tmp_name'];

// Appeler la fonction pour supprimer les doublons de noms de colonnes
removeDuplicateHeaders($tmpFilePath);

// Inclure le fichier de connexion à la base de données
include 'includes/connexion_bdd.php';

// Vérifier si un fichier a été uploadé
if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    // Récupérer le chemin temporaire du fichier
    $tmpFilePath = $_FILES['csv_file']['tmp_name'];

    // Créer un objet Reader pour le fichier CSV
    $csv = Reader::createFromPath($tmpFilePath, 'r');
    $csv->setHeaderOffset(0); // Spécifier que la première ligne contient les en-têtes

    // Récupérer les en-têtes uniques
    $uniqueHeaders = array_unique($csv->getHeader());

    // Colonnes que vous souhaitez extraire
    $columnsToExtract = ['Nom', 'Prénom', 'Adresse 1', 'Code Postal', 'Ville', 'Adresse E-mail', 'Tél. (SMS)'];

    // Fonction pour obtenir la latitude et la longitude à partir de l'adresse complète
    function getLatLongFromAddress($address)
    {
        $geocodeUrl = 'https://nominatim.openstreetmap.org/search?q=' . urlencode($address) . '&format=json&limit=1';
        $geocodeResponse = file_get_contents($geocodeUrl);

        if ($geocodeResponse !== false) {
            $geocodeData = json_decode($geocodeResponse, true);

            if (!empty($geocodeData) && isset($geocodeData[0]['lat']) && isset($geocodeData[0]['lon'])) {
                return [
                    'latitude' => $geocodeData[0]['lat'],
                    'longitude' => $geocodeData[0]['lon']
                ];
            }
        }

        return null;
    }

    $insertionOccurred = false;
    
    foreach ($csv as $row) {
        // Initialiser des variables pour stocker les valeurs des colonnes
        $nom = '';
        $prenom = '';
        $adresseComplete = '';
        $email = '';
        $telephone = '';
        $statut = 'en attente'; // Initialise le statut à "en attente"
        $statut_stage_2 = 'en attente'; // Initialise le statut_stage_2 à "en attente"

        // Extraire uniquement les valeurs des colonnes spécifiées
        foreach ($columnsToExtract as $columnName) {
            if (isset($row[$columnName])) {
                switch ($columnName) {
                    case 'Nom':
                        $nom = $row[$columnName];
                        break;
                    case 'Prénom':
                        $prenom = $row[$columnName];
                        break;
                    case 'Adresse 1':
                        $adresseComplete = $row['Adresse 1'] . ', ' . $row['Code Postal'] . ' ' . $row['Ville'];
                        break;
                    case 'Adresse E-mail':
                        $email = $row[$columnName];
                        break;
                    case 'Tél. (SMS)':
                        $telephone = $row[$columnName];
                        break;
                }
            }
        }

        // Géocodage de l'adresse pour obtenir les coordonnées de latitude et longitude
        $addressEncoded = urlencode($adresseComplete);
        $geocodeUrl = "https://nominatim.openstreetmap.org/search?format=json&q=" . $addressEncoded;
        $opts = [
            'http' => [
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $geocodeResponse = file_get_contents($geocodeUrl, false, $context);
        $geocodeData = json_decode($geocodeResponse, true);

        if ($geocodeResponse !== false) {
            $geocodeData = json_decode($geocodeResponse, true);

            if (!empty($geocodeData) && isset($geocodeData[0]['lat']) && isset($geocodeData[0]['lon'])) {
                $latitude = $geocodeData[0]['lat'];
                $longitude = $geocodeData[0]['lon'];

                $queryCheck = "SELECT COUNT(*) FROM professionnel WHERE mail = ?";
                $stmtCheck = $dbh->prepare($queryCheck);
                $stmtCheck->execute([$email]);
                $count = $stmtCheck->fetchColumn();
        
                if ($count == 0) {
                    // L'adresse e-mail n'existe pas, nous pouvons effectuer l'insertion
                    $query = "INSERT INTO professionnel (nom, prenom, adresse, latitude, longitude, telephone, mail, statut, statut_stage_2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $dbh->prepare($query);
                    $insertResult = $stmt->execute([$nom, $prenom, $adresseComplete, $latitude, $longitude, $telephone, $email, $statut, $statut_stage_2]);
        
                    if ($insertResult) {
                        $insertionOccurred = true;
                    }
                }
            }
        }
    }
    if (!$insertionOccurred) {
        $message = 'Aucune nouvelle insertion car les professionnels existent déjà';
    } elseif ($insertionOccurred && $insertResult) {
        $message = 'Insertion réussie';
    } else {
        $message = 'L\'insertion a échoué';
    }
    
    // Rediriger avec le message
    header("Location: ajout_csv.php?message=" . urlencode($message));
    exit();
}
