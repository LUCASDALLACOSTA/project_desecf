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

<head>

    <?php
    session_start();
    if (!isset($_SESSION['connected']) && $_SESSION['connected'] !== true) {
        $_SESSION['message'] = "Vous ne pouvez pas accéder à cette page sans être connecté.";
        header("Location: index.php");
        exit();
    }
    ?>
    <title>Arret à proximité</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center">
            <img src="assets/img/logo.png" alt="Logo" class="logo">
            <ul>
                <ul>
                    <?php
                    echo '<li><a href="./"></span>&nbsp;Accueil</a></li>';
                    if (isset($_SESSION['connected']) && $_SESSION['connected'] === true) {
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

<div class="scrollable-content">
    <?php
    // Chemin vers le fichier GeoJSON
    $geojson_file = 'includes/transport/stations_de_metro.geojson';

    // Vérifiez si les paramètres de latitude et de longitude sont présents dans l'URL
    if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
        // Récupérez les valeurs de latitude et de longitude depuis l'URL
        $latitude = floatval($_GET['latitude']);
        $longitude = floatval($_GET['longitude']);

        // Chargez le contenu du fichier GeoJSON
        $geojson = file_get_contents($geojson_file);

        if ($geojson !== false) {
            // Convertir le GeoJSON en tableau PHP
            $data = json_decode($geojson, true);

            // Liste des stations de métro à moins de 1 km
            $stations_proches = array();

            // Distance maximale (en mètres) pour considérer une station comme proche
            $distance_max = 1000; // 1 kilomètre équivaut à 1000 mètres

            // Fonction pour calculer la distance entre deux points géographiques (en mètres)
            function calculerDistance($lat1, $lon1, $lat2, $lon2)
            {
                $earth_radius = 6371; // Rayon de la Terre en kilomètres
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);
                $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                $distance = $earth_radius * $c * 1000; // En mètres
                return $distance;
            }

            // Parcourez les stations de métro et vérifiez si elles sont à moins de 1 km
            foreach ($data['features'] as $feature) {
                $lon = $feature['properties']['geo_point_2d']['lon'];
                $lat = $feature['properties']['geo_point_2d']['lat'];
                $distance = calculerDistance($latitude, $longitude, $lat, $lon);

                if ($distance <= $distance_max) {
                    $nom = $feature['properties']['nom'];
                    $ligne = $feature['properties']['ligne'];

                    // Recherchez si la station existe déjà dans le tableau
                    $station_existante = false;
                    foreach ($stations_proches as &$station) {
                        if ($station['nom'] === $nom) {
                            // Ajoutez la ligne à la station existante
                            $station['lignes'][] = $ligne;
                            $station_existante = true;
                            break;
                        }
                    }

                    // Si la station n'existe pas encore, ajoutez-la avec la ligne
                    if (!$station_existante) {
                        $stations_proches[] = array(
                            'nom' => $nom,
                            'lignes' => array($ligne), // Utilisez un tableau pour stocker les lignes
                            'distance' => $distance
                        );
                    }
                }
            }

            // Triez le tableau des stations proches par distance croissante
            usort($stations_proches, function ($a, $b) {
                return $a['distance'] - $b['distance'];
            });

            // Affichez les stations de métro proches sous forme de tableau
            echo '<h1>Stations de métro à moins de 1 km du POI :</h1>';
            if (!empty($stations_proches)) {
                echo '<table border="1">';
                echo '<tr><th>Nom de la station</th><th>Lignes</th><th>Distance (mètres)</th></tr>';
                foreach ($stations_proches as $station_proche) {
                    echo '<tr>';
                    echo '<td>' . $station_proche['nom'] . '</td>';
                    echo '<td>' . implode(', ', $station_proche['lignes']) . '</td>';
                    echo '<td>' . round($station_proche['distance'], 0) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo 'Aucune station de métro à moins de 1 km de la position spécifiée.';
            }
        } else {
            echo 'Erreur lors de la lecture du fichier GeoJSON.';
        }
    } else {
        echo 'Les paramètres de latitude et de longitude sont manquants dans l\'URL.';
    }
    ?>



    <!-- ARRET DE BUS !-->



    <?php
    // Chemin vers le fichier GeoJSON des arrêts de bus
    $geojson_file_bus = 'includes/transport/arrets_de_bus.geojson';

    // Vérifiez si les paramètres de latitude et de longitude sont présents dans l'URL
    if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
        // Récupérez les valeurs de latitude et de longitude depuis l'URL
        $latitude = floatval($_GET['latitude']);
        $longitude = floatval($_GET['longitude']);

        // Chargez le contenu du fichier GeoJSON des arrêts de bus
        $geojson_bus = file_get_contents($geojson_file_bus);

        if ($geojson_bus !== false) {
            // Convertir le GeoJSON en tableau PHP
            $data_bus = json_decode($geojson_bus, true);

            // Liste des arrêts de bus à moins de 300 mètres
            $arrets_proches_bus = array();

            // Distance maximale (en mètres) pour considérer un arrêt comme proche
            $distance_max_bus = 1000;

            // Parcourez les arrêts de bus et vérifiez si ils sont à moins de 300 mètres
            foreach ($data_bus['features'] as $feature) {
                $lon_bus = $feature['properties']['geo_point_2d']['lon'];
                $lat_bus = $feature['properties']['geo_point_2d']['lat'];
                $distance_bus = calculerDistance($latitude, $longitude, $lat_bus, $lon_bus);

                if ($distance_bus <= $distance_max_bus) {
                    $nom_bus = $feature['properties']['nom_log'];
                    $lignes_bus = $feature['properties']['conc_ligne'];

                    $arrets_proches_bus[] = array(
                        'nom' => $nom_bus,
                        'lignes' => $lignes_bus,
                        'distance' => $distance_bus
                    );
                }
            }

            // Triez le tableau des arrêts de bus proches par distance croissante
            usort($arrets_proches_bus, function ($a, $b) {
                return $a['distance'] - $b['distance'];
            });

            // Affichez les arrêts de bus proches sous forme de tableau
            echo '<h1>Arrêts de bus Tisséo à moins de 1km du POI :</h1>';
            if (!empty($arrets_proches_bus)) {
                echo '<table border="1">';
                echo '<tr><th>Nom de l\'arrêt</th><th>Lignes de bus</th><th>Distance (mètres)</th></tr>';
                foreach ($arrets_proches_bus as $arret_proche_bus) {
                    echo '<tr>';
                    echo '<td>' . $arret_proche_bus['nom'] . '</td>';
                    echo '<td>' . $arret_proche_bus['lignes'] . '</td>';
                    echo '<td>' . round($arret_proche_bus['distance'], 0) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo 'Aucun arrêt de bus à moins de 300 mètres de la position spécifiée.';
            }
        } else {
            echo 'Erreur lors de la lecture du fichier GeoJSON des arrêts de bus.';
        }
    } else {
        echo 'Les paramètres de latitude et de longitude sont manquants dans l\'URL.';
    }
    ?>



    <!-- GARE !-->


    <?php

    $geojson_file_gares = 'includes/transport/gare_sncf.geojson'; // Mettez à jour le nom du fichier GeoJSON

    // Vérifiez si les paramètres de latitude et de longitude sont présents dans l'URL
    if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
        // Récupérez les valeurs de latitude et de longitude depuis l'URL
        $latitude = floatval($_GET['latitude']);
        $longitude = floatval($_GET['longitude']);

        // Chargez le contenu du fichier GeoJSON des gares SNCF en Occitanie
        $geojson_gares = file_get_contents($geojson_file_gares);

        if ($geojson_gares !== false) {
            // Convertir le GeoJSON en tableau PHP
            $data_gares = json_decode($geojson_gares, true);

            // Tableau pour stocker les informations sur les gares proches
            $gares_proches = array();

            // Nombre de gares à afficher (dans ce cas, 2 gares les plus proches)
            $nombre_de_gares = 2;

            // Parcourez les gares et calculez les distances
            foreach ($data_gares['features'] as $feature) {
                $lon_gare = $feature['geometry']['coordinates'][0];
                $lat_gare = $feature['geometry']['coordinates'][1];
                $distance_gare = calculerDistance($latitude, $longitude, $lat_gare, $lon_gare);

                // Ajoutez les informations sur la gare au tableau
                $gares_proches[] = array(
                    'nom' => $feature['properties']['toponyme'],
                    'departement' => $feature['properties']['dep'],
                    'distance' => $distance_gare
                );
            }

            // Triez le tableau des gares proches par distance croissante
            usort($gares_proches, function ($a, $b) {
                return $a['distance'] - $b['distance'];
            });

            // Affichez les deux gares les plus proches en Occitanie
            echo '<h1>Les deux gares SNCF les plus proches en Occitanie :</h1>';
            if (count($gares_proches) >= $nombre_de_gares) {
                echo '<table border=1>';
                echo '<tr><th>Nom de la gare</th><th>Département</th><th>Distance (mètres)</th></tr>';
                for ($i = 0; $i < $nombre_de_gares; $i++) {
                    echo '<tr>';
                    echo '<td>' . $gares_proches[$i]['nom'] . '</td>';
                    echo '<td>' . $gares_proches[$i]['departement'] . '</td>';
                    echo '<td>' . round($gares_proches[$i]['distance'], 0) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo 'Il n\'y a pas suffisamment de gares proches en Occitanie.';
            }
        } else {
            echo 'Erreur lors de la lecture du fichier GeoJSON des gares SNCF en Occitanie.';
        }
    } else {
        echo 'Les paramètres de latitude et de longitude sont manquants dans l\'URL.';
    }

    ?>

    <!-- LIGNE LIO -->

    <?php
    // Vérifiez si les paramètres de latitude et de longitude sont présents dans l'URL
    if (isset($_GET['latitude']) && isset($_GET['longitude'])) {
        // Récupérez les valeurs de latitude et de longitude depuis l'URL
        $latitude = floatval($_GET['latitude']);
        $longitude = floatval($_GET['longitude']);

        // Chemin vers le fichier GeoJSON des stations LIO
        $geojson_file_stations = 'includes/transport/arrets_lio.geojson';

        // Chargez le contenu du fichier GeoJSON des stations LIO
        $geojson_stations = file_get_contents($geojson_file_stations);

        if ($geojson_stations !== false) {
            // Convertir le GeoJSON en tableau PHP
            $data_stations = json_decode($geojson_stations, true);

            // Tableau associatif pour stocker les informations sur les stations proches par nom
            $stations_proches = array();

            // Nombre de stations à afficher (par exemple, 2 stations les plus proches)
            $nombre_de_stations = 2;

            // Parcourez les stations LIO et calculez les distances
            foreach ($data_stations['features'] as $feature) {
                $lon_station = $feature['geometry']['coordinates'][0];
                $lat_station = $feature['geometry']['coordinates'][1];
                $distance_station = calculerDistance($latitude, $longitude, $lat_station, $lon_station);

                // Nom de la station
                $nom_station = $feature['properties']['stop_name'];

                // Si la station n'existe pas déjà dans le tableau, ajoutez-la
                if (!isset($stations_proches[$nom_station])) {
                    $stations_proches[$nom_station] = array(
                        'nom' => $nom_station,
                        'departement' => $feature['properties']['nom_departement'],
                        'distance' => $distance_station
                    );
                } else {
                    // Si la station existe déjà dans le tableau, mettez à jour la distance si elle est plus proche
                    if ($distance_station < $stations_proches[$nom_station]['distance']) {
                        $stations_proches[$nom_station]['distance'] = $distance_station;
                    }
                }
            }

            // Triez le tableau des stations proches par distance croissante
            usort($stations_proches, function ($a, $b) {
                return $a['distance'] - $b['distance'];
            });

            // Affichez les stations LIO les plus proches
            echo '<h1>Les stations LIO les plus proches :</h1>';
            if (count($stations_proches) >= $nombre_de_stations) {
                echo '<table border=1>';
                echo '<tr><th>Nom de la station</th><th>Département</th><th>Distance (mètres)</th></tr>';
                for ($i = 0; $i < $nombre_de_stations; $i++) {
                    echo '<tr>';
                    echo '<td>' . $stations_proches[$i]['nom'] . '</td>';
                    echo '<td>' . $stations_proches[$i]['departement'] . '</td>';
                    echo '<td>' . round($stations_proches[$i]['distance'], 0) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo 'Il n\'y a pas suffisamment de stations LIO';
            }
        } else {
            echo 'Erreur lors de la lecture du fichier GeoJSON des stations LIO.';
        }
    } else {
        echo 'Les paramètres de latitude et de longitude sont manquants dans l\'URL.';
    }
    ?>
</div>