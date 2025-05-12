<?php
session_start();

// Database connection
require_once 'css/db_config.php'; // Include your database connection file
// Fetch up to 5 recent news for the slider (adjust limit as needed)
$news_limit = 5; // Configurable limit
$stmt = $pdo->query("SELECT id, title, image FROM news ORDER BY created_at DESC LIMIT $news_limit");
$news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESHS | HomePage</title>
    <link rel="stylesheet" href="css/home.css?v=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    
</head>
<body>
<div class="swiper mySwiper">
        <div class="swiper-wrapper">
            <?php foreach ($news_items as $news): ?>
                <div class="swiper-slide">
                    <img src="<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                    <div class="text-box">
                        <h2><?php echo htmlspecialchars($news['title']); ?></h2>
                        <a href="./pages/news.php" class="news-btn">Voir la news</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>



    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">
            <a href="./pages/adehsion.html">
                <img src="./assets/ES_Hammam-Sousse_logo.png" alt="Logo">
            </a>
        </div>
        <p>sponsorisé par</p>
        <div class="sponsor">
            <a href="#"><img src="./assets/Capture d'écran 2025-03-01 011926.png" alt="Sponsor"></a>
        </div>
        <ul class="link1">
            <li><a href="./pages/Billets.php">Billets</a></li>
            <li><a href="./pages/Boutique.php">Boutique de Fans</a></li>
            <li><a href="./pages/ESHStv.php">ESHS TV</a></li>
            <li><a href="./pages/adehsion.html">Histoire</a></li>
            <li><a href="./pages/services.php">Services</a></li>
        </ul>
        <ul class="links">
            <?php if (isset($_SESSION['user_nom'])): ?>
                <li class="user-menu">
                    <span class="user-name"><?php echo htmlspecialchars($_SESSION['user_nom']); ?> ▼</span>
                    <ul class="dropdown-user">
                        <li><a href="../pages/mon-compte.php">Mon compte</a></li>
                        <li><a href="./css/logout.php">Déconnexion</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="./authentification.html"><img src="./assets/2354573.png" alt="Login"></a></li>
            <?php endif; ?>
            <li><img src="./assets/iconmonstr-line-three-horizontal-lined-removebg-preview.png" alt="Menu" id="menuIcon"></li>
        </ul>
        <div class="menu-container">
            <ul id="dropdownMenu" class="dropdown-menu">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="./pages/mon-compte.php">Mon compte</a></li>
                <li><a href="./pages/Billets.php">Tickets</a></li>
                <li><a href="./pages/Boutique.php">Boutique</a></li>
                <li><a href="./pages/news.php">News</a></li>
                <li><a href="./pages/services.php">chatbot</a></li>
            </ul>
        </div>
    </nav>

    <!-- COOKIE POPUP -->
    <div id="cookie-popup" class="cookie-popup">
        <p>Utilisation de Cookies et de Données Personnelles</p>
        <p id="cok">Sur ce site Internet, nous utilisons des cookies et des fonctions similaires pour traiter les informations sur les terminaux et les données personnelles. Le consentement est volontaire et peut être révoqué à tout moment.</p>
        <button id="accept-cookies">Accepter</button>
        <button id="refuser-cookies">Refuser</button>
    </div>

    <!-- SHOP NOW SECTION -->
    <div class="shop-now">
        <img src="./assets/485768178_773808948487003_890146890912057789_n.jpg" alt="Shop Now">
        <div class="shop-now-content">
            <h2>Explorez Notre Boutique ESHS</h2>
            <button onclick="window.location.href='./pages/Boutique.php?id=1'">Shop Now</button>
        </div>
    </div>

    <!-- MATCH SECTION -->
    <section class="match-section">
        <h2>Prochains Matchs</h2>
        <div class="matches-container">
            <div class="match-box main-match" onclick="window.location.href='./match-info.html?id=1'">
                <div class="teams">
                    <span class="logoa"><img src="./assets/ES_Hammam-Sousse_logo.png" alt=""></span>
                    <span class="logov"><img src="./assets/Club_sportif_de_Hammam_Lif_logo-removebg-preview.png" alt=""></span>
                    <span class="team">ESHS</span>
                    <span class="vs">VS</span>
                    <span class="team">Hammam_Lif</span>
                </div>
                <div class="match-info">
                    <p>📅 15 Avril 2025 | 🏟 STADE MUNICIPALE BOUALI LAHWAR</p>
                </div>
                <button class="details-btn" onclick="event.stopPropagation(); window.location.href='../match-info.html?id=1'">Détails</button>
                <span><img src="./assets/images__1_-removebg-preview.png" alt=""></span>
            </div>
            <div class="match-box" onclick="window.location.href='match-info.html?id=2'">
                <div class="teams">
                    <span class="logoa"><img src="./assets/images__2_-removebg-preview.png" alt=""></span>
                    <span class="logov"><img src="./assets/ES_Hammam-Sousse_logo.png" alt=""></span>
                    <span class="team">Jandouba Sport</span>
                    <span class="vs">VS</span>
                    <span class="team">ESHS</span>
                </div>
                <div class="match-info">
                    <p>📅 22 Avril 2025 | 🏟 STADE MUNICIPALE DE JANDOUBA</p>
                </div>
                <button class="details-btn" onclick="event.stopPropagation(); window.location.href='match-info.html?id=2'">Détails</button>
                <span><img src="./assets/images__1_-removebg-preview.png" alt=""></span>
            </div>
            <div class="match-box" onclick="window.location.href='match-info.html?id=3'">
                <div class="teams">
                    <span class="logoa"><img src="./assets/ES_Hammam-Sousse_logo.png" alt=""></span>
                    <span class="logov"><img src="./assets/AS-Oued-Ellil-removebg-preview.png" alt=""></span>
                    <span class="team">ESHS</span>
                    <span class="vs">VS</span>
                    <span class="team">OUED_LIL</span>
                </div>
                <div class="match-info">
                    <p>📅 30 Avril 2025 | 🏟 STADE MUNICIPALE BOUALI LAHWAR</p>
                </div>
                <button class="details-btn" onclick="event.stopPropagation(); window.location.href='match-info.html?id=3'">Détails</button>
                <span><img src="./assets/images__1_-removebg-preview.png" alt=""></span>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    <!-- SPONSORS SECTION -->
    <div class="sponsors">
        <h5>Partenaire premium de ESHS</h5>
        <ul>
            <li><a href="https://www.meublatex.com/"><img src="./assets/10947362_1014799278548037_8091963854321357740_o-removebg-preview.png" alt=""></a></li>
            <li><a href="https://www.elmouradi.com/"><img src="./assets/images__5_-removebg-preview.png" alt=""></a></li>
            <li><a href="https://www.groupe-sfbt.com/boga"><img src="./assets/images__4_-removebg-preview.png" alt=""></a></li>
            <li><a href="#"><img src="./assets/Capture d'écran 2025-03-01 011926.png" alt=""></a></li>
        </ul>
    </div>

    <div class="section-divider"></div>

    <?php 
    // Définir le chemin de base pour les assets
    $base_path = './';
    include 'includes/footer.php'; 
    ?>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>
```
