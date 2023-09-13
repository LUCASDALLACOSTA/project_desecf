<?php
// Incluez le fichier de connexion à la base de données
include 'includes/connexion_bdd.php';

// Vérifiez si le paramètre "professionnel_id" a été transmis
if (isset($_POST['id_professionnel'])) {
    $professionnelId = $_POST['id_professionnel'];

    // Préparez et exécutez la requête de suppression
    $query = "DELETE FROM professionnel WHERE id_professionnel = ?";
    $stmt = $dbh->prepare($query);
    $success = $stmt->execute([$professionnelId]);

    if ($success) {
        // La suppression a réussi
        $response = ['success' => true];
    } else {
        // La suppression a échoué
        $response = ['success' => false];
    }

    // Retournez la réponse JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Paramètre manquant
    $response = ['success' => false];
    header('Content-Type: application/json');
    echo json_encode($response);
}

