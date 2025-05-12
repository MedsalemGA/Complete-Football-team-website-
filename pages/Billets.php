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

// Handle form submission for adding to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $match_id = (int)$_POST['match_id'];
    $category = in_array($_POST['category'], ['Standard', 'VIP']) ? $_POST['category'] : 'Standard';
    $quantity = (int)$_POST['quantity'];

    // Validation
    if ($match_id <= 0 || $quantity < 1 || $quantity > 10) {
        header("Location: Billets.php?error=Invalid parameters");
        exit();
    }

    // Fetch match details
    $stmt = $conn->prepare("SELECT * FROM matches WHERE id = ?");
    $stmt->execute([$match_id]);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$match) {
        header("Location: Billets.php?error=Match not found");
        exit();
    }

    // Calculate price
    $price = $category == 'VIP' ? $match['vip_price'] : $match['standard_price'];

    // Ajouter au panier
    $cart_key = 'ticket_' . $match_id . '_' . $category;
    if (!isset($panier[$cart_key])) {
        $panier[$cart_key] = [
            'type' => 'ticket',
            'match_id' => $match_id,
            'opponent' => $match['opponent'],
            'date' => $match['match_date'],
            'category' => $category,
            'quantite' => $quantity,
            'prix' => $price
        ];
    } else {
        $panier[$cart_key]['quantite'] += $quantity;
    }
    $_SESSION['panier'] = $panier;

    header("Location: Billets.php?show_cart=true");
    exit();
}

// Handle supprimer action
if (isset($_GET['supprimer'])) {
    $cart_key = $_GET['supprimer'];
    if (isset($panier[$cart_key])) {
        unset($panier[$cart_key]);
        $_SESSION['panier'] = $panier;
    }
    header("Location: Billets.php?show_cart=true");
    exit();
}

// Fetch upcoming matches
$stmt = $conn->query("SELECT * FROM matches WHERE match_date >= CURDATE() ORDER BY match_date");
$matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul du total du panier (tickets only)
$total = 0;
foreach ($panier as $item) {
    if (isset($item['type'], $item['prix'], $item['quantite']) && $item['type'] === 'ticket') {
        $total += $item['prix'] * $item['quantite'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billets - ESHS</title>
    <link rel="stylesheet" href="../css/billet.css?v=1">
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

    <!-- Match Gallery -->
    <section class="match-gallery">
        <h1>🎟️ Billets ESHS</h1>
        <p class="tagline">Vivez la passion du football avec vos billets !</p>
        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <h2>🏟️ Matches à venir</h2>
        <div class="match-grid">
            <?php foreach ($matches as $match): ?>
                <div class="match-card">
                    
                    <div class="details">
                        <h3>ESHS vs <?php echo htmlspecialchars($match['opponent']); ?></h3>
                        <p>Date: <?php echo date('d/m/Y H:i', strtotime($match['match_date'])); ?></p>
                        <p>Lieu: <?php echo htmlspecialchars($match['venue']); ?></p>
                        <p>Prix: Standard <?php echo $match['standard_price']; ?> DT | VIP <?php echo $match['vip_price']; ?> DT</p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
                        <select name="category" required>
                            <option value="Standard">Standard (<?php echo $match['standard_price']; ?> DT)</option>
                            <option value="VIP">VIP (<?php echo $match['vip_price']; ?> DT)</option>
                        </select>
                        <input type="number" name="quantity" min="1" max="10" value="1" required>
                        <button type="submit" name="add_to_cart">Acheter</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="cart-link">
            <a href="Billets.php?show_cart=true">Voir le panier</a>
        </div>
    </section>

    <!-- Cart Display -->
    <?php if (isset($_GET['show_cart']) && $_GET['show_cart'] === 'true' && !empty($panier)): ?>
        <section class="cart-container">
            <h2>Votre Panier</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Article</th>
                        <th>Détails</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($panier as $cart_key => $item): ?>
                        <?php
                        if (!isset($item['type'], $item['quantite'], $item['prix']) || $item['type'] !== 'ticket') {
                            continue;
                        }
                        $total_item = $item['prix'] * $item['quantite'];
                        $nom = "Ticket: ESHS vs " . htmlspecialchars($item['opponent']);
                        $details = htmlspecialchars($item['category']) . " (" . date('d/m/Y H:i', strtotime($item['date'])) . ")";
                        ?>
                        <tr>
                            <td><?php echo $nom; ?></td>
                            <td><?php echo $details; ?></td>
                            <td>TND<?php echo number_format($item['prix'], 2); ?></td>
                            <td><?php echo $item['quantite']; ?></td>
                            <td>TND<?php echo number_format($total_item, 2); ?></td>
                            <td><a href="Billets.php?supprimer=<?php echo urlencode($cart_key); ?>&show_cart=true" class="btn">Supprimer</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total-price">Total: TND<?php echo number_format($total, 2); ?></div>
            <div class="payment-section">
                <form method="POST" action="paiement_billets.php">
                    <button type="submit" name="commander" class="payment-btn">Commander</button>
                </form>
            </div>
        </section>
    <?php endif; ?>

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
