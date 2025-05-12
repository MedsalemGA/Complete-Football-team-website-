<?php
session_start();
require_once '../config.php'; // Inclure la classe Database

$database = new Database();
$conn = $database->connect();

// Handle search and filter
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$price_range = isset($_GET['price_range']) ? $_GET['price_range'] : 'all';
$where_clauses = [];
$params = [];

if ($search_query) {
    $where_clauses[] = "nom LIKE ?";
    $params[] = "%$search_query%";
}

if ($price_range !== 'all') {
    switch ($price_range) {
        case '0-20':
            $where_clauses[] = "prix BETWEEN 0 AND 20";
            break;
        case '20-50':
            $where_clauses[] = "prix BETWEEN 20 AND 50";
            break;
        case '50+':
            $where_clauses[] = "prix > 50";
            break;
    }
}

$query = "SELECT id, nom, image_path, prix FROM produit";
if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}
$stmt = $conn->prepare($query);
$stmt->execute($params);
$produits = $stmt->fetchAll();

// Gestion du panier
$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];

// Nettoyer les anciennes données du panier
foreach ($panier as $cart_key => $item) {
    if (!is_array($item)) {
        unset($panier[$cart_key]);
    }
}
$_SESSION['panier'] = $panier;

// Ajouter un produit au panier
if (isset($_GET['ajouter'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../css/login.php");
        exit();
    }
    $produit_id = (int)$_GET['ajouter'];
    $stmt = $conn->prepare("SELECT prix FROM produit WHERE id = ?");
    $stmt->execute([$produit_id]);
    $produit = $stmt->fetch();

    if ($produit) {
        $cart_key = $produit_id;
        if (!isset($panier[$cart_key])) {
            $panier[$cart_key] = [
                'produit_id' => $produit_id,
                'quantite' => 1,
                'type' => null,
                'taille' => null,
                'prix' => $produit['prix']
            ];
        } else {
            $panier[$cart_key]['quantite'] += 1;
        }
        $_SESSION['panier'] = $panier;
    }
    $redirect = "boutique.php";
    if ($search_query || $price_range !== 'all') {
        $redirect .= "?";
        if ($search_query) $redirect .= "search=" . urlencode($search_query);
        if ($price_range !== 'all') $redirect .= ($search_query ? "&" : "") . "price_range=" . urlencode($price_range);
    }
    header("Location: $redirect");
    exit();
}

// Supprimer un produit du panier
if (isset($_GET['supprimer'])) {
    $cart_key = $_GET['supprimer'];
    unset($panier[$cart_key]);
    $_SESSION['panier'] = $panier;
    $redirect = "boutique.php";
    if ($search_query || $price_range !== 'all') {
        $redirect .= "?";
        if ($search_query) $redirect .= "search=" . urlencode($search_query);
        if ($price_range !== 'all') $redirect .= ($search_query ? "&" : "") . "price_range=" . urlencode($price_range);
    }
    header("Location: $redirect");
    exit();
}

// Calcul du total du panier
$total = 0;
foreach ($panier as $cart_key => $item) {
    if (is_array($item) && isset($item['prix'], $item['quantite'])) {
        $total += $item['prix'] * $item['quantite'];
    }
}

// Simulation du paiement
if (isset($_POST['payer'])) {
    header("Location: ../css/paiement.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique - ESHS</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto+Condensed:wght@700&display=swap">
    <link rel="stylesheet" href="../css/boutique.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="adehsion.html">
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

    <div class="container">
        <h1>Bienvenue sur notre boutique</h1>

        <!-- Search and Filter Section -->
        <div class="search-filter">
            <form method="GET" action="boutique.php" class="search-bar">
                <input type="text" name="search" placeholder="Rechercher un produit..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">🔍</button>
            </form>
            <div class="price-filter">
                <h3>Filtrer par prix</h3>
                <form method="GET" action="boutique.php">
                    <label><input type="radio" name="price_range" value="all" <?php echo $price_range === 'all' ? 'checked' : ''; ?>> Tous</label>
                    <label><input type="radio" name="price_range" value="0-20" <?php echo $price_range === '0-20' ? 'checked' : ''; ?>> 0 - 20 TND</label>
                    <label><input type="radio" name="price_range" value="20-50" <?php echo $price_range === '20-50' ? 'checked' : ''; ?>> 20 - 50 TND</label>
                    <label><input type="radio" name="price_range" value="50+" <?php echo $price_range === '50+' ? 'checked' : ''; ?>> 50+ TND</label>
                    <?php if ($search_query): ?>
                        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                    <?php endif; ?>
                    <button type="submit">Appliquer</button>
                </form>
            </div>
        </div>

        <!-- Message de succès après paiement -->
        <?php if (isset($success_message)): ?>
            <div class="alert"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Affichage des produits -->
        <div class="product-list">
            <?php if (empty($produits)): ?>
                <p>Aucun produit trouvé<?php echo $search_query ? " pour \"$search_query\"" : ''; ?>.</p>
            <?php else: ?>
                <?php foreach ($produits as $produit): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($produit['image_path']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                        <h2><?php echo htmlspecialchars($produit['nom']); ?></h2>
                        <p>Prix: TND<?php echo number_format($produit['prix'], 2); ?></p>
                        <a href="../pages/product_detail.php?id=<?php echo $produit['id']; ?>" class="btn">Détails</a>
                        <a href="boutique.php?ajouter=<?php echo $produit['id']; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?><?php echo $price_range !== 'all' ? ($search_query ? '&' : '?') . 'price_range=' . urlencode($price_range) : ''; ?>" class="btn">Ajouter au panier</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Affichage du panier -->
        <div class="cart-container">
            <h2>Votre Panier</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Type/Taille</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($panier as $cart_key => $item) {
                        if (!is_array($item) || !isset($item['produit_id'], $item['quantite'], $item['prix'])) {
                            continue;
                        }
                        $stmt = $conn->prepare("SELECT nom FROM produit WHERE id = ?");
                        $stmt->execute([$item['produit_id']]);
                        $produit = $stmt->fetch();
                        $nom = $produit ? $produit['nom'] : 'Produit inconnu';
                        $total_produit = $item['prix'] * $item['quantite'];
                        $type_taille = $item['type'] ?: ($item['taille'] ?: '-');
                        echo "<tr>
                                <td>" . htmlspecialchars($nom) . "</td>
                                <td>" . htmlspecialchars($type_taille) . "</td>
                                <td>TND" . number_format($item['prix'], 2) . "</td>
                                <td>" . htmlspecialchars($item['quantite']) . "</td>
                                <td>TND" . number_format($total_produit, 2) . "</td>
                                <td><a href='boutique.php?supprimer=" . urlencode($cart_key) . ($search_query ? "&search=" . urlencode($search_query) : "") . ($price_range !== 'all' ? ($search_query ? "&" : "?") . "price_range=" . urlencode($price_range) : "") . "' class='btn'>Supprimer</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Total du panier -->
            <div class="total-price">Total: TND<?php echo number_format($total, 2); ?></div>

            <!-- Bouton pour passer à la commande -->
            <div class="payment-section">
                <form method="POST">
                    <button type="submit" name="payer" class="payment-btn">Passer la commande</button>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/main.js"></script>
    <script>
        // Toggle dropdown menu
        document.getElementById('menuIcon').addEventListener('click', () => {
            const dropdownMenu = document.getElementById('dropdownMenu');
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });
    </script>
</body>
</html>
