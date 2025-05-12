<?php
session_start();
require_once '../config.php'; // Inclure la classe Database

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../css/login.php");
    exit();
}

// Connexion à la base de données
$database = new Database();
$conn = $database->connect();

// Gestion du panier
$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];

// Calcul du total des billets
$total = 0;
foreach ($panier as $item) {
    if (isset($item['type'], $item['prix'], $item['quantite']) && $item['type'] === 'ticket') {
        $total += $item['prix'] * $item['quantite'];
    }
}

// Handle payment confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer_paiement'])) {
    // Record ticket purchases
    foreach ($panier as $cart_key => $item) {
        if (isset($item['type']) && $item['type'] === 'ticket') {
            $total_price = $item['prix'] * $item['quantite'];
            $stmt = $conn->prepare("INSERT INTO ticket_purchases (user_id, match_id, category, quantity, total_price, purchase_date) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$_SESSION['user_id'], $item['match_id'], $item['category'], $item['quantite'], $total_price]);
            // Remove ticket from cart
            unset($panier[$cart_key]);
        }
    }
    $_SESSION['panier'] = $panier;
    header("Location: confirmation_billets.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement des Billets - ESHS</title>
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

    <!-- Payment Section -->
    <section class="payment-container">
        <h1>Paiement des Billets</h1>
        <?php if (empty(array_filter($panier, fn($item) => isset($item['type']) && $item['type'] === 'ticket'))): ?>
            <p class="error">Aucun billet dans le panier.</p>
        <?php else: ?>
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>Match</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($panier as $item): ?>
                        <?php
                        if (!isset($item['type'], $item['quantite'], $item['prix']) || $item['type'] !== 'ticket') {
                            continue;
                        }
                        $total_item = $item['prix'] * $item['quantite'];
                        $nom = "ESHS vs " . htmlspecialchars($item['opponent']);
                        $details = htmlspecialchars($item['category']) . " (" . date('d/m/Y H:i', strtotime($item['date'])) . ")";
                        ?>
                        <tr>
                            <td><?php echo $nom; ?></td>
                            <td><?php echo $details; ?></td>
                            <td>TND<?php echo number_format($item['prix'], 2); ?></td>
                            <td><?php echo $item['quantite']; ?></td>
                            <td>TND<?php echo number_format($total_item, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total-price">Total: TND<?php echo number_format($total, 2); ?></div>
            <div class="payment-section">
                <form method="POST">
                    <button type="submit" name="confirmer_paiement" class="payment-btn">Confirmer le paiement</button>
                </form>
                <a href="Billets.php" class="back-link">Retour aux billets</a>
            </div>
        <?php endif; ?>
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
