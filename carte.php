<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>CARTE DESECF</title>
    <meta content="PROJET DESECF" name="description">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />

    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin="">
    </script>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top ">
        <div class="container d-flex align-items-center">
            <ul>
                <li><a href="./"></span>&nbsp;Accueil</a></li>
                <li><a href="connexion.php"><span></span>&nbsp;Connexion</a></li>
                <li><a href="carte.php">&nbsp;Carte</a></li>
                <li><a href="ajout_csv.php">&nbsp;Ajout via csv</a></li>
                <ul>
        </div>
    </header>
    <!-- End Header -->


    <div id="map" style="height: 700px"></div>

    <!-- CSS -->
    <!-- style des markers -->
    <style>
        img.huechange {
            filter: hue-rotate(120deg);
        }
    </style>
    <?php

    include 'includes/connexion_bdd.php'; // Connexion à la base de données

    // Requête SQL pour sélectionner toutes les entrées de la table "professionnel"
    $sql = "SELECT * FROM professionnel";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // Récupération des données dans un tableau
    $professionnels = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $professionnels[] = $row;
    }

    ?>
    <script>
        var map = L.map('map').setView([43.5937766, 1.4710826], 14); //point où on se situe 

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { //affichage de la map
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        if ("geolocation" in navigator) {
            // Obtention de la position actuelle
            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                // Création du marqueur avec les coordonnées de la position actuelle
                var marker = L.marker([latitude, longitude]).addTo(map);
                marker._icon.classList.add("huechange");

                // Changer la couleur de la balise du marqueur en noir
                marker.setIcon(L.icon({
                    iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-black.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                }));

                // Ajout d'une pop-up au marqueur
                marker.bindPopup("Vous êtes ici").openPopup();

                // Personnalisation du style de la pop-up
                marker.getPopup().getElement().style.color = "black";

                // Centrer la carte sur la position actuelle
                map.setView([latitude, longitude], 13);
            });
        } else {
            console.log("La géolocalisation n'est pas prise en charge par votre navigateur.");
        }

        var tous = L.layerGroup();
        var disponible = L.layerGroup();
        var nondisponible = L.layerGroup();
        var enattente = L.layerGroup();

        // Changer la couleur de la balise du marqueur en fonction du statut
        var nonDisponibleIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var disponibleIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        var enAttenteIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
        // Boucle pour créer les marqueurs pour chaque professionnel
        <?php foreach ($professionnels as $professionnel) { ?>
            var latitude = <?php echo $professionnel['latitude']; ?>;
            var longitude = <?php echo $professionnel['longitude']; ?>;
            var nom = "<?php echo $professionnel['nom']; ?>";
            var prenom = "<?php echo $professionnel['prenom']; ?>";
            var adresse = "<?php echo $professionnel['adresse']; ?>";
            var telephone = "<?php echo $professionnel['telephone']; ?>";
            var mail = "<?php echo $professionnel['mail']; ?>";
            var statut = "<?php echo $professionnel['statut']; ?>";
            var dureeStage = "<?php echo $professionnel['duree_stage']; ?>";
            var dateDebutStage = "<?php echo $professionnel['date_debut_stage']; ?>";
            var id = "<?php echo $professionnel['id_professionnel']; ?>";

            // Création du marqueur avec les coordonnées du professionnel
            var marker = L.marker([latitude, longitude]).addTo(map);

            // Création du contenu de la pop-up avec les informations du professionnel
            var popupContent = "<strong>Nom :</strong> " + nom + "<br>";
            popupContent += "<strong>Prénom :</strong> " + prenom + "<br>";
            popupContent += "<strong>Adresse :</strong> " + adresse + "<br>";
            popupContent += "<strong>Téléphone :</strong> " + telephone + "<br>";
            popupContent += "<strong>E-mail :</strong> " + mail + "<br>";
            popupContent += "<strong>Statut :</strong> " + statut + "<br>";
            popupContent += "<strong>Durée de stage :</strong> " + dureeStage + "<br>";
            popupContent += "<strong>Date de début du stage :</strong> " + dateDebutStage + "<br>";
            popupContent += "<a href='modifier_professionnel.php?id=" + id + "'>Modifier</a>";

            // Ajout de la pop-up au marqueur
            marker.bindPopup(popupContent);
            // Ajout du marqueur à la couche correspondante en fonction du statut
            if (statut === 'disponible') {
                marker.addTo(disponible);
                marker.setIcon(disponibleIcon);
            } else if (statut === 'non disponible') {
                marker.addTo(nondisponible);
                marker.setIcon(nonDisponibleIcon);
            } else {
                marker.addTo(enattente);
                marker.setIcon(enAttenteIcon);
            }

            // Ajout du marqueur à la couche "tous"
            marker.addTo(tous);
        <?php } ?>

        var overlayMaps = {
            "Tous": tous,
            "Disponible": disponible,
            "Non disponible": nondisponible,
            "En attente": enattente
        };

        var layerControl = L.control.layers(null, overlayMaps).addTo(map);
    </script>


</body>

</html>