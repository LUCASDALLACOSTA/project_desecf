<?php

if (!isset($_SESSION['Type_user'])) {
    
    if (file_exists("./connexion.php")) {
        header('Location:./connexion.php?error=1');
    } elseif (file_exists('../connexion.php')) {
        header('Location:../connexion.php?error=1');
    }
}
