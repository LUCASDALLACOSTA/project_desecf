<?php
//Connexion BDD

$whitelist = array(
    '127.0.0.1',
    '::1'
);

if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {

    //Mode test localhost
    $host_name = 'localhost';
    $database = 'desecf';
    $user_name = 'root';

    try {
        $dbh = new PDO("mysql:host=$host_name;dbname=$database;", $user_name);
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
    
} 