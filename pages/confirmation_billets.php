<?php
session_start();
require_once '../config.php'; // Inclure la classe Database

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../css/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Paiement - ESHS</title>
    <link rel="stylesheet" href="../css/billet.css">
</head>
<body>
    <!-- Navbar (from boutique.php) -->
    <nav class="navbar">
        <div class="logo">
            <a href="../index.php">
                <img src="../assets/ES_Hammam-Sousse_logo.png" alt="Logo">
            </a>
        </div>
        <p>sponsorisé par</p>
        <div class="sponsor">
            <a href="#"><img src="../assets/Capture d'écran 2025-03-01 011926.png" alt="Sponsor"></a>
        </div>
        <ul class="link1">
            <li><a href="../index.php">Home</a></li>
            <li><a href="Billets.php">Billets</a></li>
            <li><a href="Boutique.php">Boutique de Fans</a></li>
            <li><a href="ESHStv.php">ESHS TV</a></li>
            <li><a href="adehsion.html">Histoire</a></li>
            <li><a href="services.php">Services</a></li>
        </ul>
        <ul class="links">
            <?php if (isset($_SESSION['user_nom'])): ?>
                <li class="user-menu">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_nom']); ?> ▼</span>
                    <ul class="dropdown-user">
                        <li><a href="../pages/mon-compte.php">Mon compte</a></li>
                        <li><a href="../css/logout.php">Déconnexion</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="../authentification.html"><img src="../assets/2354573.png" alt="Login"></a></li>
            <?php endif; ?>
            <li><img src="../assets/iconmonstr-line-three-horizontal-lined-removebg-preview.png" alt="Menu" id="menuIcon"></li>
        </ul>
        <div class="menu-container">
            <ul id="dropdownMenu" class="dropdown-menu">
                <li><a href="../index.php">Accueil</a></li>
                <li><a href="mon-compte.php">Mon compte</a></li>
                <li><a href="Billets.php">Tickets</a></li>
                <li><a href="Boutique.php">Boutique</a></li>
                <li><a href="news.php">News</a></li>
                <li><a href="services.php">chatbot</a></li>
            </ul>
        </div>
    </nav>

    <!-- Confirmation Section -->
    <section class="confirmation-container">
        <h1>Paiement Confirmé</h1>
        <p class="success">Votre paiement pour les billets a été effectué avec succès !</p>
        <p>Merci de votre achat. Vous recevrez une confirmation par email.</p>
        <a href="Billets.php" class="back-link">Retour aux billets</a>
    </section>

    <!-- Footer (from boutique.php) -->
    <footer>
        <div class="footer-content">
            <h3>Suivez nous sur les réseaux sociaux</h3>
            <ul>
                <li><a href="https://www.instagram.com/espoir.sportif.hammam.sousse/"><img src="../assets/pngtree-instagram-social-platform-icon-png-image_6315976-removebg-preview.png" alt="Instagram"></a></li>
                <li><a href="https://www.facebook.com/profile.php?id=100030548511719"><img src="../assets/png-clipart-facebook-logo-computer-icons-facebook-logo-facebook-thumbnail-removebg-preview.png" alt="Facebook"></a></li>
                <li><a href="https://mail.google.com/mail/u/0/#inbox"><img src="../assets/png-transparent-mail-google-gmail-google-s-logo-icon-removebg-preview.png" alt="Email"></a></li>
                <li><a href="https://www.youtube.com/@EspoirSportifDeHammamSousse/videos"><img src="../assets/free-youtube-logo-icon-2431-thumb-removebg-preview.png" alt="YouTube"></a></li>
            </ul>
        </div>
        <div class="bas">
            <span><a href="https://www.ftf.org.tn/fr/"><img src="../assets/Logo_federation_tunisienne_de_football.svg-removebg-preview.png" alt="FTF"></a></span>
            <span><img src="../assets/images__1_-removebg-preview.png" alt="Ligue 2"></span>
        </div>
        <div class="footer-bottom">
            <p>Allez ESHS ! ⚽ © 2025 Espoir Sportif Hammam-Sousse. Tous droits réservés</p>
        </div>
    </footer>

    <script>
        // Toggle dropdown menu
        document.getElementById('menuIcon').addEventListener('click', () => {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });
    </script>
</body>
</html>
