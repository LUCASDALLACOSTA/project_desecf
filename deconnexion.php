<?php
session_start();

// Détruire la session actuelle
session_destroy();

$message_deconnexion = "Vous êtes déconnecté.";
header("Location: index.php?message_deconnexion=" . urlencode($message_deconnexion));
exit();
?>
