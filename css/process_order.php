<?php
session_start();
require_once 'db_config.php';
header('Content-Type: application/json');

// Vérification de l'authentification
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit;
}

// Vérification du panier
if (!isset($_POST['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Panier vide']);
    exit;
}

$cart = json_decode($_POST['cart'], true);

if (!is_array($cart)) {
    echo json_encode(['success' => false, 'message' => 'Format de panier invalide']);
    exit;
}

try {
    // Valider chaque item du panier
    foreach ($cart as $item) {
        if (!isset($item['id'], $item['quantity']) || !is_numeric($item['id']) || !is_numeric($item['quantity'])) {
            throw new Exception("Format d'article invalide");
    }
    
        $item['id'] = (int)$item['id'];
        $item['quantity'] = (int)$item['quantity'];
    
        if ($item['quantity'] <= 0) {
            throw new Exception("Quantité invalide pour le produit ID {$item['id']}");
    }
}
    $pdo->beginTransaction();
    
    // 1. Vérifier la disponibilité des produits et calculer le total
    $total = 0;
    foreach ($cart as $item) {
        $stmt = $pdo->prepare("SELECT prix, stock FROM produit WHERE id = ?");
        $stmt->execute([$item['id']]);
        $product = $stmt->fetch();
        
        if (!$product) {
            throw new Exception("Produit ID {$item['id']} non trouvé");
        }
        
        if ($product['stock'] < $item['quantity']) {
            throw new Exception("Stock insuffisant pour le produit ID {$item['id']}");
        }
        
        $total += $product['prix'] * $item['quantity'];
    }
    
    // 2. Enregistrer la vente dans la table ventes
    $stmt = $pdo->prepare("INSERT INTO ventes (user_id, date_vente, montant_total, statut) 
                          VALUES (?, NOW(), ?, 'en traitement')");
    $stmt->execute([$_SESSION['user_id'], $total]);
    $saleId = $pdo->lastInsertId();
    
    // 3. Enregistrer les articles vendus et mettre à jour les stocks
    foreach ($cart as $item) {
        // Ajouter à la table des lignes de vente (si elle existe)
        // Adaptez selon votre structure exacte
        $stmt = $pdo->prepare("INSERT INTO ligne_vente (vente_id, produit_id, quantite, prix_unitaire) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $saleId,
            $item['id'],
            $item['quantity'],
            $item['price']
        ]);
        
        // Mettre à jour le stock
        $stmt = $pdo->prepare("UPDATE produit SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$item['quantity'], $item['id']]);
    }
    
    $pdo->commit();
    
    echo json_encode(['success' => true, 'order_id' => $saleId]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>