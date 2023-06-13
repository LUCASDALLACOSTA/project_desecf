<?php
include 'includes/connexion_bdd.php'; //connexion bdd


if (isset($_GET['disconnect']) && !empty($_GET['disconnect']) && !isset($_SESSION['Type_user'])) {
    $message_warning = "Vous avez été déconnecté !";
}
if (isset($_GET['information']) && !empty($_GET['information']) && !isset($_SESSION['Type_user'])) {
    $message_information = "Votre code confidentiel à bien été changé !";
}
if (isset($_GET['error']) && !empty($_GET['error']) && !isset($_SESSION['Type_user'])) {
    if ($_GET['error'] == 1) {
        $message_error = "Vous devez être connecté pour accéder à cette page !";
    } else {
        $message_error = "Vous n'avez pas accès à cette page !";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
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
  background-image: url("assets/img/portfolio/fondAccueil.png");
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

.logo {
    width: 50px;
    height: auto;
    margin-right: 10px;
}

#navbar ul {
    list-style: none;
    display: flex;
}

#navbar ul li {
    margin-right: 10px;
}

#navbar ul li a {
    color: #fff;
    text-decoration: none;
    padding: 5px;
}

#navbar ul li a:hover {
    color: #ffd700;
}

/* Hero Section Styles */
#hero {
    height: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.card {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 10px;
    padding: 20px;
}

.card-title {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 20px;
}

.alert {
    margin-bottom: 20px;
    padding: 10px;
    border-radius: 5px;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.alert-info {
    background-color: #d1ecf1;
    color: #0c5460;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
}

form {
    margin-bottom: 20px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
}

.btn-primary {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.back-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 40px;
    height: 40px;
    background-color: #007bff;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    text-decoration: none;
    transition: opacity 0.3s ease;
    opacity: 0.7;
}

.back-to-top:hover {
    opacity: 1;
}

</style>
<head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<title>Connexion - PROJET WEB P2</title>
<meta content="PROJET WEB P2 (Bachelor RPI)" name="description">
<!-- Favicons -->
<link href="assets/img/favicon.png" rel="icon">
<link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

<!-- Google Fonts -->
<link href="assets/fonts/google-font.css" rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="assets/vendor/aos/aos.css" rel="stylesheet">
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

<!-- Template Main CSS File -->
<link href="assets/css/style.css" rel="stylesheet">

</head>

<body>
<!-- ======= Header ======= -->
<header id="header" class="fixed-top ">
<div class="container d-flex align-items-center">

<!-- <h1 class="logo me-auto"><a href="./">PROJET WEB P1</a></h1>-->
<!-- Uncomment below if you prefer to use an image logo -->
<img src="assets/img/portfolio/logo.png" alt="Logo" class="logo">

<!-- navbar -->
<nav id="navbar" class="navbar">
<ul>
<li><a class="nav-link scrollto" href="./"><i class='bx bxs-home'></i></span>&nbsp;Accueil</a></li>
<?php if (!isset($_SESSION['Email'])) { ?><li><a class="nav-link scrollto active" href="connexion.php"><span><i class='bx bx-log-in-circle'></i></span>&nbsp;Connexion</a></li>
    <?php } else { ?>
        <li class="dropdown"><a href="mon-espace.php"><span><i class="bi bi-chevron-down"></i>&nbsp;Mon espace</span></a>
        <ul>
        <?php if ($_SESSION['Type_user'] == 2 || $_SESSION['Type_user'] == 3) { ?>
            <li><a href="recherche-feuille-emargement.php"><span><i class='bx bxs-file-find' id="icons"></i>&nbsp;Recherche feuille émargement</a></span></li>
            <li><a href="nouveau-cours.php"><span><i class='bx bxs-plus-circle' id="icons"></i>&nbsp;Nouveau cours</a></span></li>
            <?php } ?>
            <?php if ($_SESSION['Type_user'] == 3) { ?>
                <li><a href="nouveau-enseignant.php"><span><i class='bx bxs-user-plus' id="icons"></i>&nbsp;Nouveau Enseignant</a></span></li>
                <li><a href="nouveau-eleve.php"><span><i class='bx bxs-user-plus' id="icons"></i>&nbsp;Nouveau Elève</a></span></li>
                <li><a href="nouvelle-classe.php"><span><i class='bx bxs-plus-circle' id="icons"></i>&nbsp;Nouvelle Classe</a></span></li>
                <li><a href="nouveau-groupe.php"><span><i class='bx bxs-group' id="icons"></i>&nbsp;Nouveau Groupe</a></span></li>
                <?php } ?>
                </ul>
                </li>
                <li><a class="nav-link scrollto" href="deconnexion.php"><span><i class='bx bx-log-out-circle'></i></span>&nbsp;Deconnexion</a></li>
                <?php } ?>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
                </nav>
                <!-- .navbar -->
                </div>
                </header>
                <!-- End Header -->
                
                <!-- ======= Hero Section ======= -->
                <section id="hero" class="d-flex align-items-center" style="height: auto;">
                <div class="container h-100 mt-3">
                <div class="row justify-content-sm-center h-100">
                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-9">
                <div class="card shadow-lg">
                <div class="card-body p-4">
                <h3 class="fs-4 card-title fw-bold mb-4">Connexion</h3>
                
                <?php if (isset($message_error) && !isset($message_warning) && !isset($message_information)) { ?>
                    <div class="alert alert-danger" role="alert">
                    <?php echo "$message_error"; ?>
                    </div>
                    <?php } ?>
                    <?php if (isset($message_information) && !isset($message_warning) && !isset($message_error)) { ?>
                        <div class="alert alert-info" role="info">
                        <?php echo "$message_information"; ?>
                        </div>
                        <?php } ?>
                        <?php if (isset($message_warning) && !isset($message_error) && !isset($message_information)) { ?>
                            <div class="alert alert-warning" role="warning">
                            <?php echo "$message_warning"; ?>
                            </div>
                            <?php } ?>
                            <form role="form" method="post" class="needs-validation" autocomplete="on">
                            <div class="mb-3">
                            <label class="mb-2 text-muted" for="email">Adresse Mail</label>
                            <input id="email" type="email" class="form-control" name="mailconnect" value="<?php if (isset($mailconnect)) {
                                echo $mailconnect;
                            } ?>" required>
                            </div>
                            <div class="mb-3">
                            <div class="mb-2 w-100">
                            <label class="text-muted" for="password">Mot de passe</label>
                            </div>
                            <input id="password" type="password" class="form-control" name="mdpconnect" minlength="6" maxlength="6" required>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                            <div class="form-check">
                            <input type="checkbox" name="rememberme" id="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">Se souvenir de moi</label>
                            </div>
                            </div>
                            <div class="mb-3">
                            <button name="submit" type="submit" class="btn btn-primary ms-auto">
                            Connexion
                            </button>
                            </div>
                            <?php if (isset($message_alert)) { ?>
                                <div class="alert alert-danger" role="alert">
                                <?php echo "$message_alert"; ?>
                                </div>
                                <?php } ?>
                                </form>
                                </div>
                                </div>
                                
                                </div>
                                </div>
                                </div>
                                </section>
                                <!-- End Hero -->
                                
                                <div id="preloader"></div>
                                <a href="./" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
                                
                                <!-- Vendor JS Files -->
                                <script src="assets/vendor/aos/aos.js"></script>
                                <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
                                <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
                                <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
                                <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
                                <script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
                                
                                <!-- Template Main JS File -->
                                <script src="assets/js/main.js"></script>
                                
                                </body>
                                
                                </html>