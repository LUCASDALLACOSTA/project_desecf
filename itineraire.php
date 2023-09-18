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

    .logo {
        width: 50px;
        height: auto;
        margin-right: 10px;
    }

    .footer-banner {
        max-height: 100px;
        /* Hauteur maximale */
        text-align: center;
        /* Centrer le texte */
        width: 100%;
        /* Largeur de 100% pour s'adapter à la largeur de l'écran */
        background-color: #f8f8f8;
        /* Couleur de fond */
        padding: 10px 0;
        /* Espacement interne */
    }

    #map {
        height: 800px;
    }
</style>

<?php
session_start();
if (!isset($_SESSION['connected']) && $_SESSION['connected'] !== true) {
    $_SESSION['message'] = "Vous ne pouvez pas accéder à cette page sans être connecté.";
    header("Location: index.php");
    exit();
}
$latitudeArrivee = isset($_GET['latitude_arrivee']) ? floatval($_GET['latitude_arrivee']) : 0;
$longitudeArrivee = isset($_GET['longitude_arrivee']) ? floatval($_GET['longitude_arrivee']) : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<header id="header" class="fixed-top">
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

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calcul d'itinéraire</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</head>

<body>
    <h1>Afficher l'itinéraire</h1>

    <div id="map"></div>

    <script>
        var pointDepart = null;

        var vert = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });


        var rouge = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var pointArrivee = L.latLng(<?php echo $latitudeArrivee; ?>, <?php echo $longitudeArrivee; ?>);

        var map = L.map('map').setView([<?php echo $latitudeArrivee; ?>, <?php echo $longitudeArrivee; ?>], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var controlGeocoder = L.Control.geocoder({
            defaultMarkGeocode: false
        }).on('markgeocode', function(e) {
            var latlng = e.geocode.center;

            // Créez le marqueur de départ ici
            if (pointDepart !== null) {
                map.removeLayer(pointDepart);
            }

            pointDepart = L.marker(latlng, {
                icon: vert
            }).addTo(map);

            pointDepart.bindPopup('<b>Point de départ</b>').openPopup();

            calculerItineraire();
        }).addTo(map);

        var markerArrivee = L.marker(pointArrivee, {
            icon: rouge
        }).addTo(map);

        markerArrivee.bindPopup('<b>Point d\'arrivée</b>').openPopup();

        var iti = null;

        function calculerItineraire() {
            // Supprimez l'itinéraire précédent s'il existe
            if (iti !== null) {
                map.removeLayer(iti);
            }

            // Vérifiez si pointDepart et pointArrivee sont définis
            if (pointDepart !== null && pointArrivee !== null) {
                // Convertissez les points de départ et d'arrivée en format LatLng
                var latLngDepart = pointDepart.getLatLng();
                var latLngArrivee = L.latLng(pointArrivee.lat, pointArrivee.lng);

                // Créez un objet 'Control' de routage ici
                var control = L.Routing.control({
                    waypoints: [
                        latLngDepart, // Point de départ
                        latLngArrivee // Point d'arrivée
                    ],
                    routeWhileDragging: true // Permet de mettre à jour l'itinéraire pendant le déplacement du point de départ
                }).addTo(map);

                // Écoutez l'événement 'routeselected' pour obtenir l'itinéraire
                control.on('routeselected', function(e) {
                    iti = e.route; // Stocke l'itinéraire
                    L.geoJSON(iti).addTo(map); // Affiche l'itinéraire
                });
            } else {
                console.error('Marker de départ ou point d\'arrivée non défini.');
            }
        }

        // Afficher l'itinéraire initial (peut être vide si l'utilisateur n'a pas encore effectué de recherche)
        calculerItineraire();
    </script>
</body>

</html>