<?php
include 'includes/connexion_bdd.php'; //connexion bdd

?>
<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<title>PROJET DESECF</title>
<meta content="PROJET DESECF" name="description">
<link rel="stylesheet" href="css/style.css">
</head>

<body>
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
  background-image: url("assets/img/fondAccueil.png");
  background-repeat: no-repeat;
  background-size: cover;
  background-position: top;
  background-color: #f8f8f8;
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
<!-- ======= Header ======= -->
<header id="header" class="fixed-top">
<div class="container d-flex align-items-center">
  <img src="assets/img/logo.png" alt="Logo" class="logo">
  <ul>
    <li><a href="./"></span>&nbsp;Accueil</a></li>
    <li><a href="connexion.php"><span></span>&nbsp;Connexion</a></li>
    <li><a href="carte.php">&nbsp;Carte</a></li>
    <li><a href="ajout_csv.php">&nbsp;Ajout via csv</a></li>
  </ul>
</div>
</header>

<!-- End Header -->

<h1>PROJET DESECF</h1>
<h2>BLUZAT Clément - CLAVERIE Enzo - DALLA COSTA Lucas</h2>
</body>

</html>