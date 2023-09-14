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
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />

    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin="">
    </script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <img src="assets/img/logo.png" alt="Logo" class="logo">
            <ul>
                <ul>
                    <?php
                    echo '<li><a href="./"></span>&nbsp;Accueil</a></li>';
                    echo '<li><a href="connexion.php"><span></span>&nbsp;Connexion</a></li>';
                    if (isset($_SESSION['connected']) === true) {
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
    <!-- End Header -->

    <div id="map" style="height: 840px"></div>

    <?php

    include 'includes/connexion_bdd.php'; // Connexion à la base de données

    session_start();

    if (!isset($_SESSION['connected']) || $_SESSION['connected'] !== true) {
        $_SESSION['message'] = "Vous ne pouvez pas accéder à cette page sans être connecté.";
        header("Location: index.php");
        exit();
    }

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
        var map = L.map('map').setView([43.5937766, 1.4710826], 14);

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
        var dispo1er = L.layerGroup();
        var dispo2eme = L.layerGroup();
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

        var disponible1erIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-orange.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })

        var disponible2emeIcon = L.icon({
            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-yellow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })

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
            var statut_stage_2 = "<?php echo $professionnel['statut_stage_2']; ?>";
            var duree_stage_2 = "<?php echo $professionnel['duree_stage_2']; ?>";
            var date_debut_stage_2 = "<?php echo $professionnel['date_debut_stage_2']; ?>";
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
                    type_structure: type_structure,
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
                statut_stage_2: statut_stage_2,
                dureeStage: dureeStage,
                duree_stage_2: duree_stage_2,
                dateDebutStage: dateDebutStage,
                date_debut_stage_2: date_debut_stage_2,
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
                var popupContent = "<h3>Nom de la structure : </h3>" + nom_structure + "";
                popupContent += "<h3>Adresse : </h3>" + adresse + "";
                popupContent += "<h3>Type de la structure : </h3>" + type_structure + "<br>";
                popupContent += "<h4><a href='" + listeArretsProches + "'>Voir les arrêts à proximité</a></h4>";
                popupContent += "<h4><a href='itineraire.php?latitude_arrivee=" + latitude + "&longitude_arrivee=" + longitude + "'>Afficher l'itinéraire</a></h4><br>";
                popupContent += "<ul>";

                // Ajoutez les informations de chaque professionnel
                for (var i = 0; i < professionnels.length; i++) {
                    var professionnel = professionnels[i];
                    var statut = professionnel.statut;
                    var statut_stage_2 = professionnel.statut_stage_2;
                    var dureeStage = professionnel.dureeStage;
                    var duree_stage_2 = professionnel.duree_stage_2;
                    var dateDebutStage = professionnel.dateDebutStage;
                    var date_debut_stage_2 = professionnel.date_debut_stage_2;

                    popupContent += "<h3 style='text-align:center;'>Professionnel n° : " + (i + 1) + "</h3>";
                    popupContent += "<li><strong>Nom :</strong> " + professionnel.nom + "</li>";
                    popupContent += "<li><strong>Prénom :</strong> " + professionnel.prenom + "</li>";
                    popupContent += "<li><strong>Téléphone :</strong> " + professionnel.telephone + "</li>";
                    popupContent += "<li><strong>E-mail :</strong> " + professionnel.mail + "</li>";
                    popupContent += "<h4 style='text-align:center;'>Premier Stage</h4>";
                    popupContent += "<li><strong>Statut:</strong> " + statut + "</li>";
                    popupContent += "<li><strong>Durée de stage:</strong> " + dureeStage + "</li>";
                    popupContent += "<li><strong>Date de début du stage:</strong> " + dateDebutStage + "</li>";
                    popupContent += "<h4 style='text-align:center;'>Deuxième Stage</h4>";
                    popupContent += "<li><strong>Statut:</strong> " + statut_stage_2 + "</li>";
                    popupContent += "<li><strong>Durée de stage:</strong> " + duree_stage_2 + "</li>";
                    popupContent += "<li><strong>Date de début du stage:</strong> " + date_debut_stage_2 + "</li>";
                    popupContent += "<li><a href='modifier_professionnel.php?id=" + professionnel.id + "'>Modifier</a></li>";
                    popupContent += "<button onclick='supprimerProfessionnel(" + professionnel.id + ")'>Supprimer</button>";

                    // Ajoutez le marqueur à la couche correspondante en fonction du statut
                    if (statut === 'disponible' && statut_stage_2 === 'disponible') {
                        marker.setIcon(disponibleIcon);
                        marker.addTo(disponible);
                    } else if (statut === 'non disponible' && statut_stage_2 === 'non disponible') {
                        marker.setIcon(nonDisponibleIcon);
                        marker.addTo(nondisponible);
                    } else if (statut === 'non disponible' && statut_stage_2 === 'disponible') {
                        marker.setIcon(disponible2emeIcon);
                        marker.addTo(dispo2eme);
                    } else if (statut === 'disponible' && statut_stage_2 === 'non disponible') {
                        marker.setIcon(disponible1erIcon);
                        marker.addTo(dispo1er);
                    } else {
                        marker.setIcon(enAttenteIcon);
                        marker.addTo(enattente);
                    }
                }

                popupContent += "</ul>";

                // Ajout de la pop-up au marqueur
                marker.bindPopup(popupContent);
                // Ajout du marqueur à la couche "tous"
                marker.addTo(tous);

                function supprimerProfessionnel(professionnelId) {
                    if (confirm("Êtes-vous sûr de vouloir supprimer ce professionnel ?")) {
                        var xhr = new XMLHttpRequest();
                        xhr.open("POST", "supprimer_professionnel.php", true);
                        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4) {
                                if (xhr.status === 200) {
                                    var response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        alert("Le professionnel a été supprimé avec succès.");
                                        header("Location: carte.php");
                                    } else {
                                        alert("La suppression du professionnel a échoué.");
                                    }
                                } else {
                                    alert("Erreur lors de la suppression du professionnel.");
                                }
                            }
                        };
                        xhr.send("id_professionnel=" + professionnelId);
                    }
                }

            }

        <?php } ?>

        var overlayMaps = {
            "Tous": tous,
            "Disponible": disponible,
            "Non disponible": nondisponible,
            "1er stage disponible": dispo1er,
            "2eme stage disponible": dispo2eme,
            "En attente": enattente
        };

        var layerControl = L.control.layers(null, overlayMaps).addTo(map);
    </script>

    <div class="footer-banner">
        <h2 style="margin: 0;">PROJET DESECF</h2>
        <h3 style="text-align: center;">BLUZAT Clément - CLAVERIE Enzo - DALLA COSTA Lucas</h3>
    </div>
</body>

</html>