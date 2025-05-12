<?php
session_start();
require_once '../config.php'; // Inclure la classe Database

// Vérifier si l'utilisateur est connecté

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESHS TV</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/home.css">
</head>
<body class="bg-gray-900 text-white font-montserrat">
    <!-- Header (from index.php, matching Billets.php) -->
    <nav class="navbar bg-gradient-to-r from-black to-gray-800 shadow-lg p-4 flex items-center justify-between">
        <div class="logo">
            <a href="../index.php">
                <img src="../assets/ES_Hammam-Sousse_logo.png" alt="Logo" class="h-12">
            </a>
        </div>
        <p class="text-yellow-400 text-sm">Sponsorisé par</p>
        <div class="sponsor">
            <a href="#"><img src="../assets/Capture d'écran 2025-03-01 011926.png" alt="Sponsor" class="h-10"></a>
        </div>
        <ul class="link1 flex space-x-6 text-yellow-400 font-semibold hidden md:flex">
            <li><a href="../index.php">Home</a></li>
            <li><a href="../pages/Billets.php">Billets</a></li>
            <li><a href="../pages/Boutique.php">Boutique de Fans</a></li>
            <li><a href="../pages/ESHStv.php">ESHS TV</a></li>
            <li><a href="../pages/adehsion.html">Histoire</a></li>
            <li><a href="../pages/services.php">Services</a></li>
        </ul>
        <ul class="links flex items-center space-x-4">
            <?php if (isset($_SESSION['user_nom'])): ?>
                <li class="user-menu relative">
                    <span class="user-name cursor-pointer text-yellow-400"><?php echo htmlspecialchars($_SESSION['user_nom']); ?> ▼</span>
                    <ul class="dropdown-user absolute hidden bg-black border-2 border-yellow-400 rounded-md mt-2 right-0">
                        <li><a href="../pages/mon-compte.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Mon compte</a></li>
                        <li><a href="../css/logout.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Déconnexion</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="../authentification.php"><img src="../assets/2354573.png" alt="Login" class="h-6"></a></li>
            <?php endif; ?>
            <li><img src="../assets/iconmonstr-line-three-horizontal-lined-removebg-preview.png" alt="Menu" id="menuIcon" class="h-6 cursor-pointer md:hidden"></li>
        </ul>
        <div class="menu-container md:hidden">
            <ul id="dropdownMenu" class="dropdown-menu hidden absolute bg-black border-2 border-yellow-400 rounded-md w-48 right-4 top-16">
                <li><a href="../index.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Accueil</a></li>
                <li><a href="../pages/mon-compte.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Mon compte</a></li>
                <li><a href="../pages/Billets.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Billets</a></li>
                <li><a href="../pages/Boutique.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Boutique</a></li>
                <li><a href="news.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">News</a></li>
                <li><a href="../pages/services.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Services</a></li>
            </ul>
        </div>
    </nav>

    <!-- Banner -->
    <header class="eshstv-banner bg-gradient-to-r from-black to-gray-800 text-center py-16 relative">
        <div class="banner-content">
            <h1 class="text-5xl md:text-6xl font-bold text-yellow-400 uppercase tracking-wide animate-glow">📺 ESHS TV</h1>
            <p class="mt-4 text-lg md:text-xl text-gray-300">Revivez les moments forts de l'ESHS !</p>
        </div>
    </header>

    <!-- Section Title -->
    <h2 class="section-title text-3xl md:text-4xl font-bold text-yellow-400 text-center py-8">Nos Vidéos</h2>

    <!-- Video Gallery -->
    <section class="video-gallery container mx-auto px-4 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="video-box bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition hover:scale-105 hover:shadow-xl">
            <video controls class="w-full h-48 object-cover">
                <source src="../assets/[ملخص مباراة الأمل الرياضي بحمام سوسة  1 و  مستقبل الرجيش  0 2022 [ودية.mp4" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
            <h3 class="p-4 text-yellow-400 font-semibold text-center">Résumé du match amical contre Rejich</h3>
        </div>
        <div class="video-box bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition hover:scale-105 hover:shadow-xl">
            <video controls class="w-full h-48 object-cover">
                <source src="../assets/[ملخص مباراة الأمل الرياضي بحمام سوسة والنادي الرياضي الصفاقسي سبتمبر 2021 [ودية.mp4" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
            <h3 class="p-4 text-yellow-400 font-semibold text-center">Résumé du match amical contre le CSS</h3>
        </div>
        <div class="video-box bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition hover:scale-105 hover:shadow-xl">
            <video controls class="w-full h-48 object-cover">
                <source src="../assets/entrainement equipe de hamem sousse foot.mp4" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
            <h3 class="p-4 text-yellow-400 font-semibold text-center">Séance d'entraînement de cadet à la plage</h3>
        </div>
        <div class="video-box bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition hover:scale-105 hover:shadow-xl">
            <video controls class="w-full h-48 object-cover">
                <source src="../assets/أهداف مباراة الأمل الرياضي بحمام سوسة  و الملعب الإفريقي بمنزل بورقيبة  _  ESHS 1-1 SAMB.mp4" type="video/mp4">
                Votre navigateur ne supporte pas la lecture de vidéos.
            </video>
            <h3 class="p-4 text-yellow-400 font-semibold text-center">Résumé du match contre Manzel Bourguiba</h3>
        </div>
    </section>

    <!-- TV Logo Section -->
    <div class="tv text-center py-16 bg-gradient-to-b from-gray-900 to-black">
        <a href="adehsion.php" class="inline-block transform transition hover:scale-105 hover:rotate-3">
            <img src="../assets/ES_Hammam-Sousse_logo.png" alt="ESHS Logo" class="w-64 h-64 md:w-80 md:h-80 rounded-full border-8 border-yellow-400 shadow-2xl">
        </a>
    </div>

    <!-- Footer (from index.php, matching Billets.php) -->
    <footer class="bg-gradient-to-r from-black to-gray-800 text-center py-8">
        <div class="footer-content">
            <h3 class="text-yellow-400 text-xl font-semibold mb-4">Suivez-nous sur les réseaux sociaux</h3>
            <ul class="flex justify-center space-x-4">
                <li><a href="https://www.instagram.com/espoir.sportif.hammam.sousse/"><img src="../assets/pngtree-instagram-social-platform-icon-png-image_6315976-removebg-preview.png" alt="Instagram" class="h-10"></a></li>
                <li><a href="https://www.facebook.com/profile.php?id=100030548511719"><img src="../assets/png-clipart-facebook-logo-computer-icons-facebook-logo-facebook-thumbnail-removebg-preview.png" alt="Facebook" class="h-10"></a></li>
                <li><a href="https://mail.google.com/mail/u/0/#inbox"><img src="../assets/png-transparent-mail-google-gmail-google-s-logo-icon-removebg-preview.png" alt="Email" class="h-10"></a></li>
                <li><a href="https://www.youtube.com/@EspoirSportifDeHammamSousse/videos"><img src="../assets/free-youtube-logo-icon-2431-thumb-removebg-preview.png" alt="YouTube" class="h-10"></a></li>
            </ul>
        </div>
        <div class="bas flex justify-center space-x-4 my-4">
            <span><a href="https://www.ftf.org.tn/fr/"><img src="../assets/Logo_federation_tunisienne_de_football.svg-removebg-preview.png" alt="FTF" class="h-12"></a></span>
            <span><img src="../assets/images__1_-removebg-preview.png" alt="Ligue 2" class="h-12"></span>
        </div>
        <div class="footer-bottom">
            <p class="text-yellow-400">Allez ESHS ! ⚽ © 2025 Espoir Sportif Hammam-Sousse. Tous droits réservés</p>
        </div>
    </footer>

    <script>
        // Toggle dropdown menu
        document.getElementById('menuIcon').addEventListener('click', () => {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.classList.toggle('hidden');
        });

        // Show/hide user dropdown
        const userMenu = document.querySelector('.user-menu');
        if (userMenu) {
            userMenu.addEventListener('click', () => {
                const dropdown = userMenu.querySelector('.dropdown-user');
                dropdown.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
