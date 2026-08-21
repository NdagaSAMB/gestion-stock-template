<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/php/Class/Admin.php");

if (isset($_POST['conectee'])) {
    extract($_POST);
    // Récupérer le résultat de l'admin authentifié 
    $resultat = Admin::estAdmin($email, $mdp);

    if (isset($resultat['email'])) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['admin'] = $resultat;
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Modèle d'administration Bootstrap">
    <meta name="keywords"
        content="admin, estimations, bootstrap, entreprise, professionnel, créatif, facture, html5, responsive, projets">
    <meta name="author" content="Dreamguys - Modèle d'administration Bootstrap">
    <meta name="robots" content="noindex, nofollow">
    <title>AMITAM Store - Connexion</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="account-page">

    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <form class="login-userset" method="post" action="./signin.php">
                        <div class="login-logo">
                            <img src="assets/img/logo.png" alt="logo">
                        </div>
                        <div class="login-userheading">
                            <h3>Se connecter</h3>
                            <h4>Veuillez vous connecter à votre compte</h4>
                        </div>
                        <div class="form-login">
                            <label>Adresse e-mail</label>
                            <div class="form-addons">
                                <input type="text" placeholder="Entrez votre adresse e-mail" name="email">
                                <img src="assets/img/icons/mail.svg" alt="icone-email">
                                <?php if (isset($resultat)): ?>
                                    <?php if ($resultat === FAUX_EMAIL): ?>
                                        <p style="color:red; text-align: center">Adresse e-mail invalide</p>
                                    <?php endif ?>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Mot de passe</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input" placeholder="Entrez votre mot de passe" name="mdp">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                            <div>
                                <?php if (isset($resultat)): ?>
                                    <?php if ($resultat === FAUX_MDP): ?>
                                        <p style="color:red; text-align: center">Mot de passe incorrect</p>
                                    <?php endif ?>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="form-login">
                            <button class="btn btn-login" type="submit" name="conectee" value="conectee">Se connecter</button>
                        </div>
                    </form>
                </div>
                <div class="login-img">
                    <img src="assets/img/login.jpg" alt="image-connexion">
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>