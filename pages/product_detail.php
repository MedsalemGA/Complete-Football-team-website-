<?php
session_start();
require_once '../config.php'; // Inclure la classe Database

// Vérifier si l'utilisateur est connecté


// Vérifier si l'ID du produit est fourni
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../pages/boutique.php");
    exit();
}

$produit_id = $_GET['id'];
$database = new Database();
$conn = $database->connect();

// Récupérer les détails du produit
$stmt = $conn->prepare("SELECT id, nom, image_path, prix, type, description, tailles, types FROM produit WHERE id = ?");
$stmt->execute([$produit_id]);
$produit = $stmt->fetch();

if (!$produit) {
    header("Location: ../pages/boutique.php");
    exit();
}

// Gestion de l'ajout au panier
if (isset($_POST['ajouter_panier'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../css/login.php");
        exit();
    }
    $quantite = isset($_POST['quantite']) ? (int)$_POST['quantite'] : 1;
    $type = isset($_POST['type']) ? $_POST['type'] : null;
    $taille = isset($_POST['taille']) ? $_POST['taille'] : null;

    // Définir le prix selon le type d'abonnement
    $prix = $produit['prix'];
    if ($produit['type'] == 'abonnement' && $type) {
        $prix = match ($type) {
            'Standard' => 40.00,
            'Premium' => 80.00,
            'VIP' => 200.00,
            default => $produit['prix'],
        };
    }

    $panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
    $cart_key = $produit_id . ($type ? '_' . $type : ($taille ? '_' . $taille : ''));

    if (!isset($panier[$cart_key])) {
        $panier[$cart_key] = [
            'produit_id' => $produit_id,
            'quantite' => $quantite,
            'type' => $type,
            'taille' => $taille,
            'prix' => $prix
        ];
    } else {
        $panier[$cart_key]['quantite'] += $quantite;
    }
    $_SESSION['panier'] = $panier;
    header("Location: ../pages/boutique.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du produit - <?php echo $produit['nom']; ?> - ESHS</title>
    <link rel="stylesheet" href="../css/product_detail.css?v=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Roboto+Condensed:wght@700&display=swap">
</head>
<body>

<div class="container">
    <a href="../pages/boutique.php" class="btn back-btn">Retour à la boutique</a>
    <h1><?php echo $produit['nom']; ?></h1>

    <div class="product-detail">
        <div class="product-image">
            <img src="<?php echo $produit['image_path']; ?>" alt="<?php echo $produit['nom']; ?>">
        </div>
        <div class="product-info">
            <h2><?php echo $produit['nom']; ?></h2>
            <p class="price" id="price">Prix: TND<?php echo number_format($produit['prix'], 2); ?></p>
            <p><strong>Description:</strong> <?php echo $produit['description'] ?: 'Aucune description disponible.'; ?></p>
            <form method="POST">
                <?php if ($produit['type'] == 'vetement' && !empty($produit['tailles'])): ?>
                    <label for="taille">Taille:</label>
                    <select name="taille" id="taille" required>
                        <?php
                        $tailles = explode(',', $produit['tailles']);
                        foreach ($tailles as $taille) {
                            echo "<option value='$taille'>$taille</option>";
                        }
                        ?>
                    </select>
                <?php elseif ($produit['type'] == 'ballon' && !empty($produit['tailles'])): ?>
                    <label for="taille">Taille:</label>
                    <select name="taille" id="taille" required>
                        <?php
                        $tailles = explode(',', $produit['tailles']);
                        foreach ($tailles as $taille) {
                            echo "<option value='$taille'>$taille</option>";
                        }
                        ?>
                    </select>
                <?php elseif ($produit['type'] == 'abonnement' && !empty($produit['types'])): ?>
                    <label for="type">Type d'abonnement:</label>
                    <select name="type" id="type" onchange="updatePrice()" required>
                        <?php
                        $types = explode(',', $produit['types']);
                        foreach ($types as $type) {
                            echo "<option value='$type'>$type</option>";
                        }
                        ?>
                    </select>
                <?php endif; ?>
                <label for="quantite">Quantité:</label>
                <input type="number" name="quantite" id="quantite" min="1" value="1" required>
                <button type="submit" name="ajouter_panier" class="btn">Ajouter au panier</button>
            </form>
        </div>
    </div>
</div>

<script>
function updatePrice() {
    const typeSelect = document.getElementById('type');
    const priceElement = document.getElementById('price');
    if (!typeSelect || !priceElement) return;

    const selectedType = typeSelect.value;
    let price;
    switch (selectedType) {
        case 'Standard':
            price = 40.00;
            break;
        case 'Premium':
            price = 80.00;
            break;
        case 'VIP':
            price = 200.00;
            break;
        default:
            price = <?php echo $produit['prix']; ?>;
    }
    priceElement.textContent = `Prix: TND${price.toFixed(2)}`;
}

// Set initial price for subscriptions
document.addEventListener('DOMContentLoaded', updatePrice);
</script>

</body>
</html>