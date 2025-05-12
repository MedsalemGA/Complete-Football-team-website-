<?php
session_start();
require_once __DIR__ . '../css/db_config.php';

// Rediriger si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../css/login.php?redirect=../pages/panier.php");
    exit();
}

// Traitement de la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider_commande'])) {
    if (empty($_SESSION['panier'])) {
        $error = "Votre panier est vide !";
    } else {
        $pdo->beginTransaction();
        
        try {
            // 1. Calculer le total
            $total = 0;
            foreach ($_SESSION['panier'] as $item) {
                $total += $item['prix'] * $item['quantite'];
            }

            // 2. Créer la vente
            $stmt = $pdo->prepare("
                INSERT INTO ventes (user_id, date_vente, montant_total, statut)
                VALUES (?, NOW(), ?, 'en traitement')
            ");
            $stmt->execute([$_SESSION['user_id'], $total]);
            $vente_id = $pdo->lastInsertId();

            // 3. Ajouter les lignes de vente
            foreach ($_SESSION['panier'] as $item) {
                // Vérifier le stock
                $stmt = $pdo->prepare("SELECT stock FROM produit WHERE id = ? FOR UPDATE");
                $stmt->execute([$item['id']]);
                $stock = $stmt->fetchColumn();

                if ($stock < $item['quantite']) {
                    throw new Exception("Stock insuffisant pour : " . $item['nom']);
                }

                // Insérer la ligne de vente
                $stmt = $pdo->prepare("
                    INSERT INTO ligne_vente (vente_id, produit_id, quantite, prix_unitaire)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $vente_id,
                    $item['id'],
                    $item['quantite'],
                    $item['prix']
                ]);

                // Mettre à jour le stock
                $stmt = $pdo->prepare("UPDATE produit SET stock = stock - ? WHERE id = ?");
                $stmt->execute([$item['quantite'], $item['id']]);
            }

            $pdo->commit();
            unset($_SESSION['panier']);
            $success = "Commande #$vente_id validée ! Un email de confirmation vous a été envoyé.";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Erreur : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mon Panier</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .total { font-weight: bold; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Mon Panier</h1>
    
    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php elseif (isset($success)): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['panier'])): ?>
        <table>
            <tr>
                <th>Produit</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Total</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['panier'] as $item):
                $sousTotal = $item['prix'] * $item['quantite'];
                $total += $sousTotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['nom']) ?></td>
                    <td><?= number_format($item['prix'], 2) ?> €</td>
                    <td><?= $item['quantite'] ?></td>
                    <td><?= number_format($sousTotal, 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="3">Total</td>
                <td><?= number_format($total, 2) ?> €</td>
            </tr>
        </table>

        <form method="post">
            <button type="submit" name="valider_commande">Valider la commande</button>
        </form>
    <?php else: ?>
        <p>Votre panier est vide.</p>
    <?php endif; ?>

    <a href="../pages/boutique.php">← Retour à la boutique</a>
</body>
</html>