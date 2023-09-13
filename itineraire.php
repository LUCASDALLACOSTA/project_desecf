<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calcul d'itinéraire</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.3.4/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.css" />

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
            max-height: 100px; /* Hauteur maximale */
            text-align: center; /* Centrer le texte */
            width: 100%; /* Largeur de 100% pour s'adapter à la largeur de l'écran */
            background-color: #f8f8f8; /* Couleur de fond */
            padding: 10px 0; /* Espacement interne */
        }

        #map {
            height: 800px;
        }

        .end-point-icon {
            background-color: red;
            width: 25px;
            height: 41px;
        }

        .start-point-icon {
            background-color: green;
            width: 25px;
            height: 41px;
        }
    </style>
</head>

<body>
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <img src="assets/img/logo.png" alt="Logo" class="logo">
            <ul>
                <li><a href="./"></span>&nbsp;Accueil</a></li>
                <li><a href="connexion.php"><span></span>&nbsp;Connexion</a></li>
                <li><a href="ajout_csv.php">&nbsp;Ajout via csv</a></li>
                <li><a href="ajout_professionnel.php">&nbsp;Ajouter un professionnel</a></li>
            </ul>
        </div>
    </header>

    <h1>Calcul d'itinéraire</h1>

    <div id="map"></div>

    <button id="locate-button">Obtenir ma position</button>

    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.3.4/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder@1.13.0/dist/Control.Geocoder.js"></script>
    <div id="debug"></div>
    <script>
        var map = L.map('map').setView([0, 0], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var control = L.Routing.control({
        routeWhileDragging: true,
        geocoder: L.Control.Geocoder.nominatim(),
        routeDragTimeout: 100,
        showAlternatives: true,
        altLineOptions: {
            styles: [{
                    color: 'black',
                    opacity: 0.15,
                    weight: 9
                },
                {
                    color: 'white',
                    opacity: 0.8,
                    weight: 6
                },
                {
                    color: 'blue',
                    opacity: 0.5,
                    weight: 2
                }
            ]
        },

        waypointNameFallback: function(latLng) {
            function zeroPad(n) {
                n = Math.round(n);
                return n < 10 ? '0' + n : n;
            }

            function sexagesimal(p, pos, neg) {
                var n = Math.abs(p),
                    degs = Math.floor(n),
                    mins = (n - degs) * 60,
                    secs = (mins - Math.floor(mins)) * 60,
                    frac = Math.round((secs - Math.floor(secs)) * 100);
                return (n >= 0 ? pos : neg) + degs + '°' +
                    zeroPad(mins) + ''' +
                    zeroPad(secs) + '.' + zeroPad(frac) + '"';
            }

            return sexagesimal(latLng.lat, 'N', 'S') + ' ' + sexagesimal(latLng.lng, 'E', 'W');
        },
        createMarker: function(i, waypoint) {

            return L.marker(waypoint.latLng, {
                draggable: true,
                icon: L.icon.glyph({
                    glyph: String.fromCharCode(65 + i)

                })

            })
        }
    }).addTo(map);
        var endPoint; // Point d'arrivée

        var redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var startMarker; // Marqueur du point de départ

        // Fonction pour calculer et afficher l'itinéraire
        function calculateRoute(startPoint, endPoint) {
            // Créez un objet de contrôleur d'itinéraire
            L.Routing.control({
                waypoints: [
                    startPoint,
                    endPoint
                ],
                routeWhileDragging: true // Permet de recalculer l'itinéraire en déplaçant le point de destination
            }).addTo(map);
        }

        // Fonction pour obtenir la géolocalisation
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    var startPoint = L.latLng(latitude, longitude);

                    // Supprimez le marqueur précédent (s'il existe)
                    if (map.hasLayer(startMarker)) {
                        map.removeLayer(startMarker);
                    }

                    // Ajoutez le marqueur du point de départ avec l'icône verte
                    startMarker = L.marker(startPoint, { icon: greenIcon }).addTo(map);

                    // Calculez et affichez l'itinéraire
                    calculateRoute(startPoint, endPoint);
                });
            } else {
                alert("La géolocalisation n'est pas prise en charge par votre navigateur.");
            }
        }

        // Ajoutez un bouton ou une interface utilisateur pour déclencher la géolocalisation
        document.getElementById('locate-button').addEventListener('click', getLocation);

        // Initialisez le point d'arrivée avec les coordonnées de l'URL
        endPoint = L.latLng(
            <?php echo isset($_GET['latitude_arrivee']) ? $_GET['latitude_arrivee'] : 0; ?>,
            <?php echo isset($_GET['longitude_arrivee']) ? $_GET['longitude_arrivee'] : 0; ?>
        );

        // Initialisez l'itinéraire avec les coordonnées du point d'arrivée
        calculateRoute(endPoint, endPoint); // Vous pouvez utiliser le point d'arrivée comme point de départ temporaire
    </script>
</body>

</html>
