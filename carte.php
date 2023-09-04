<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>CARTE DESECF</title>
    <meta content="PROJET DESECF" name="description">
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
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />

    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin="">
    </script>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <img src="assets/img/portfolio/logo.png" alt="Logo" class="logo">
            <ul>
                <li><a href="./"></span>&nbsp;Accueil</a></li>
                <li><a href="connexion.php"><span></span>&nbsp;Connexion</a></li>
                <li><a href="ajout_csv.php">&nbsp;Ajout via csv</a></li>
                <li><a href="ajout_professionnel.php">&nbsp;Ajouter un professionnel</a></li>
            </ul>
        </div>
    </header>
    <!-- End Header -->


    <div id="map" style="height: 700px"></div>

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
        var professionnelsParAdresse = {};

        <?php foreach ($professionnels as $professionnel) { ?>
            var latitude = <?php echo $professionnel['latitude']; ?>;
            var longitude = <?php echo $professionnel['longitude']; ?>;
            var adresse = "<?php echo $professionnel['adresse']; ?>";
            var nom = "<?php echo $professionnel['nom']; ?>";
            var prenom = "<?php echo $professionnel['prenom']; ?>";
            var telephone = "<?php echo $professionnel['telephone']; ?>";
            var mail = "<?php echo $professionnel['mail']; ?>";
            var statut = "<?php echo $professionnel['statut']; ?>";
            var dureeStage = "<?php echo $professionnel['duree_stage']; ?>";
            var dateDebutStage = "<?php echo $professionnel['date_debut_stage']; ?>";
            var type_structure = "<?php echo $professionnel['type_structure']; ?>";
            var nom_structure = "<?php echo $professionnel['nom_structure']; ?>";
            var id = "<?php echo $professionnel['id_professionnel']; ?>";

            // Créez une clé unique pour chaque adresse
            var adresseKey = latitude + "_" + longitude + "_" + adresse;

            // Vérifiez si la clé existe déjà dans l'objet
            if (!professionnelsParAdresse[adresseKey]) {
                // Si elle n'existe pas, créez une nouvelle entrée
                professionnelsParAdresse[adresseKey] = {
                    latitude: latitude,
                    longitude: longitude,
                    adresse: adresse,
                    nom_structure: nom_structure,
                    type_structure:type_structure,
                    professionnels: [] // Un tableau pour stocker les professionnels ayant la même adresse
                };
            }

            // Ajoutez ce professionnel au tableau correspondant à cette adresse
            professionnelsParAdresse[adresseKey].professionnels.push({
                nom: nom,
                prenom: prenom,
                telephone: telephone,
                mail: mail,
                statut: statut,
                dureeStage: dureeStage,
                dateDebutStage: dateDebutStage,
                type_structure: type_structure,
                nom_structure: nom_structure,
                id: id
            });

        
        // Boucle pour créer les marqueurs et les pop-ups regroupées
        for (var adresseKey in professionnelsParAdresse) {
            var info = professionnelsParAdresse[adresseKey];
            var latitude = info.latitude;
            var longitude = info.longitude;
            var adresse = info.adresse;
            var type_structure = info.type_structure;
            var professionnels = info.professionnels;
            var nom_structure = info.nom_structure;

            // Création du marqueur avec les coordonnées de l'adresse
            var marker = L.marker([latitude, longitude]).addTo(map);

            var listeArretsProches = "liste_arrets_proches.php?latitude=" + latitude + "&longitude=" + longitude;

            // Création du contenu de la pop-up avec les informations des professionnels
            var popupContent = "<h3>Nom de la structure : " + nom_structure + "</h3>";
            popupContent += "<h3>Adresse : " + adresse + "</h3>";
            popupContent += "<h3>Type de la structure : " + type_structure + "</h3>";
            popupContent += "<h3><a href='" + listeArretsProches + "'>Voir les arrêts à proximité</a></h3>";
            popupContent += "<ul>";

            // Ajoutez les informations de chaque professionnel
            for (var i = 0; i < professionnels.length; i++) {
                var professionnel = professionnels[i];
                var statut = professionnel.statut;
                popupContent += "<h3 style='text-align:center;'>Professionnel n° : " + (i + 1) + "</h3>";
                popupContent += "<li><strong>Nom :</strong> " + professionnel.nom + "</li>";
                popupContent += "<li><strong>Prénom :</strong> " + professionnel.prenom + "</li>";
                popupContent += "<li><strong>Téléphone :</strong> " + professionnel.telephone + "</li>";
                popupContent += "<li><strong>E-mail :</strong> " + professionnel.mail + "</li>";
                popupContent += "<li><strong>Statut :</strong> " + professionnel.statut + "</li>";
                popupContent += "<li><strong>Durée de stage :</strong> " + professionnel.dureeStage + "</li>";
                popupContent += "<li><strong>Date de début du stage :</strong> " + professionnel.dateDebutStage + "</li>";
                popupContent += "<li><a href='modifier_professionnel.php?id=" + professionnel.id + "'>Modifier</a></li>";

                // Ajoutez le marqueur à la couche correspondante en fonction du statut
                if (statut === 'disponible') {
                    marker.setIcon(disponibleIcon);
                } else if (statut === 'non disponible') {
                    marker.setIcon(nonDisponibleIcon);
                } else {
                    marker.setIcon(enAttenteIcon);
                }
            }

            popupContent += "</ul>";

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
        }
        <?php }?>

        var overlayMaps = {
            "Tous": tous,
            "Disponible": disponible,
            "Non disponible": nondisponible,
            "En attente": enattente
        };

        var layerControl = L.control.layers(null, overlayMaps).addTo(map);
    </script>

    <h2 style="background-color: white;">PROJET DESECF</h2>
    <h3 style="text-align: center;">BLUZAT Clément - CLAVERIE Enzo - Lucas Dallas Costa</h3>
</body>

</html>