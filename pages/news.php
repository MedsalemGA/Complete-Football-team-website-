<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'football_db';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch all news
$stmt = $pdo->query("SELECT id, title, content, image, created_at FROM news ORDER BY created_at DESC");
$news_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités - ESHS</title>
    <link rel="stylesheet" href="../css/news.css?v=1">
</head>
<body>
    <!-- Header -->
    <header class="news-banner">
        <img src="../assets/ES_Hammam-Sousse_logo.png" alt="Logo ESHS" class="logo">
        <h1>📰 Actualités ESHS</h1>
        <p class="tagline">Restez informé des dernières nouvelles de l'équipe !</p>
        <div class="nav-bar">
            <a href="../index.php">Accueil</a>
            <a href="../pages/adehsion.html">Histoire</a>
            <a href="../pages/ESHStv.php">ESHS tv</a>
            <a href="../pages/billets.php">Billets</a>
            
        </div>
    </header>

    <!-- News Gallery -->
    <section class="news-gallery">
        <h2>📰 Toutes les Actualités</h2>
        <div class="news-grid">
            <?php foreach ($news_items as $news): ?>
                <div class="news-card">
                    <img src="<?php echo htmlspecialchars($news['image']); ?>" alt="<?php echo htmlspecialchars($news['title']); ?>">
                    <div class="details">
                        <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p class="date"><?php echo date('d/m/Y', strtotime($news['created_at'])); ?></p>
                        <p><?php echo htmlspecialchars(substr($news['content'], 0, 100)) . '...'; ?></p>
                        <a href="../pages/news_details.php?id=<?php echo $news['id']; ?>">Lire plus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Logo Section -->
    <div class="tv">
        <a href="./adehsion.html">
            <img src="../assets/ES_Hammam-Sousse_logo.png" alt="Logo ESHS">
        </a>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p class="tagline">Jaune et Noir : La Passion de Hammam Sousse</p>
        <div class="social-icons">
            <a href="https://www.facebook.com/profile.php?id=100030548511719" target="_blank"><img src="../assets/png-clipart-facebook-logo-computer-icons-facebook-logo-facebook-thumbnail-removebg-preview.png" alt="" height=38px></a>
            <a href="https://www.youtube.com/@EspoirSportifDeHammamSousse/videos" target="_blank"><img src="../assets/free-youtube-logo-icon-2431-thumb-removebg-preview.png" alt="" height=38px></a>
            <a href="https://www.instagram.com/espoir.sportif.hammam.sousse/" target="_blank"><img src="../assets/pngtree-instagram-social-platform-icon-png-image_6315976-removebg-preview.png" alt="" height=32px></a>
        </div>
        <div class="mini-gallery">
            <img src="../assets/images__3_-removebg-preview.png" alt="Stade">
            <img src="../assets/Capture d'écran 2025-03-01 164840.png" alt="Équipe">
            <img src="../assets/cheering_fan.png" alt="Supporters">
        </div>
        <p class="contact">Contactez-nous : contact@eshs.tn</p>
    </footer>

    <script>
        // Parallax effect for header background
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.news-banner');
            const scroll = window.scrollY;
            header.style.setProperty('--scroll', scroll + 'px');
            if (scroll > 50) {
                header.classList.add('parallax');
            } else {
                header.classList.remove('parallax');
            }
        });
    </script>
</body>
</html>