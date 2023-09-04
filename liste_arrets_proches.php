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
        echo '<h1>Stations de métro à moins de 1 km de la position :</h1>';
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
