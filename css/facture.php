<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../css/login.php");
    exit();
}

// Vérifier si l'ID de la commande est passé en paramètre dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Aucune commande trouvée.";
    exit();
}

$commande_id = (int)$_GET['id'];

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=localhost;dbname=football_db;charset=utf8mb4", "root", "root");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les détails de la commande
    $stmt = $conn->prepare("SELECT * FROM commande WHERE id = ?");
    $stmt->execute([$commande_id]);
    $commande = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$commande) {
        echo "Commande introuvable.";
        exit();
    }

    // Récupérer les détails de l'utilisateur
    $stmt_user = $conn->prepare("SELECT nom FROM users WHERE id = ?");
    $stmt_user->execute([$commande['user_id']]);
    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Utilisateur introuvable.";
        exit();
    }

    // Récupérer les produits de la commande
    $stmt_items = $conn->prepare("
        SELECT cp.produit_id, cp.quantite, cp.prix_unitaire, p.nom, p.type
        FROM commande_produit cp
        JOIN produit p ON cp.produit_id = p.id
        WHERE cp.commande_id = ?
    ");
    $stmt_items->execute([$commande_id]);
    $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture de Commande - ESHS</title>
    <style>
        :root {
            --jaune-fonce: #FFB300; /* Jaune vibrant */
            --noir: #1A1A1A; /* Noir profond */
            --blanc: #FFFFFF; /* Blanc pur */
            --accent-jaune: #FFD700; /* Jaune clair pour accents */
            --gris: #E0E0E0; /* Gris clair pour bordures */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--noir) 30%, var(--jaune-fonce) 100%);
            color: var(--noir);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://i.imgur.com/stadium-crowd-yellow-black.jpg') no-repeat center/cover;
            opacity: 0.1;
            z-index: -1;
            filter: blur(10px);
        }

        .container {
            background: var(--blanc);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 0 0 15px rgba(255, 179, 0, 0.3);
            width: 100%;
            max-width: 900px;
            padding: 2rem;
            border: 2px solid var(--jaune-fonce);
            position: relative;
            overflow: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, var(--jaune-fonce), var(--accent-jaune));
            padding: 1.5rem;
            border-radius: 12px 12px 0 0;
            position: relative;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--noir);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header img.logo {
            max-width: 100px;
            transition: transform 0.3s ease;
        }

        .header img.logo:hover {
            transform: scale(1.1);
        }

        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            padding: 1.5rem;
            background: #F9F9F9;
            border-radius: 8px;
            margin: 1.5rem 0;
            border-left: 4px solid var(--jaune-fonce);
        }

        .details p {
            font-size: 0.95rem;
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
        }

        .details p strong {
            font-weight: 600;
            color: var(--noir);
            margin-right: 0.5rem;
        }

        .details p::before {
            content: '⚽';
            margin-right: 0.5rem;
            font-size: 0.8rem;
            color: var(--jaune-fonce);
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            background: #F9F9F9;
            border-radius: 8px;
            overflow: hidden;
        }

        .items-table th, .items-table td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid var(--gris);
        }

        .items-table th {
            background: var(--noir);
            color: var(--accent-jaune);
            text-transform: uppercase;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .items-table td {
            font-size: 0.9rem;
            color: var(--noir);
        }

        .total {
            text-align: right;
            padding: 1rem;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--noir);
            background: linear-gradient(90deg, var(--gris), var(--blanc));
            border-radius: 8px;
            margin-top: 1rem;
        }

        .total span {
            color: var(--jaune-fonce);
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: var(--noir);
            color: var(--blanc);
            border-radius: 0 0 12px 12px;
            margin-top: 2rem;
        }

        .footer p {
            font-size: 0.9rem;
        }

        .buttons {
            display: flex;
            gap: 1rem;
        }

        .back-btn, .btn-print {
            padding: 0.8rem 1.5rem;
            background: var(--jaune-fonce);
            color: var(--noir);
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            border: 2px solid var(--noir);
            transition: all 0.3s ease;
            font-size: 0.9rem;
            text-transform: uppercase;
            cursor: pointer;
        }

        .back-btn:hover, .btn-print:hover {
            background: var(--accent-jaune);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 179, 0, 0.4);
        }

        .back-btn:active, .btn-print:active {
            transform: translateY(0);
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }

            .container {
                box-shadow: none;
                border: none;
                padding: 0;
            }

            .buttons {
                display: none;
            }

            .footer {
                background: none;
                color: var(--noir);
            }

            .header {
                border-bottom: 2px solid var(--noir);
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 1rem;
            }

            .header h1 {
                font-size: 1.4rem;
            }

            .details {
                grid-template-columns: 1fr;
            }

            .items-table th, .items-table td {
                font-size: 0.8rem;
                padding: 0.6rem;
            }

            .footer {
                flex-direction: column;
                gap: 1rem;
            }

            .buttons {
                flex-direction: column;
                width: 100%;
            }

            .back-btn, .btn-print {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Facture de Commande</h1>
            <img src="../assets/ES_Hammam-Sousse_logo.png" alt="Logo" class="logo">
        </div>

        <div class="details">
            <p><strong>Nom du Client :</strong> <?= htmlspecialchars($user['nom']); ?></p>
            <p><strong>Adresse de Livraison :</strong> <?= htmlspecialchars($commande['adresse_livraison']); ?></p>
            <p><strong>Prix Total :</strong> <?= number_format($commande['prix'], 2, ',', ' ') ?> TND</p>
            <p><strong>Date de Commande :</strong> <?= htmlspecialchars($commande['date_commande']); ?></p>
        </div>

        <h2>Détails de la Commande</h2>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Type</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Aucun produit trouvé pour cette commande.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['nom']); ?></td>
                            <td><?= htmlspecialchars($item['type'] ?: '-'); ?></td>
                            <td><?= htmlspecialchars($item['quantite']); ?></td>
                            <td>TND <?= number_format($item['prix_unitaire'], 2, ',', ' '); ?></td>
                            <td>TND <?= number_format($item['quantite'] * $item['prix_unitaire'], 2, ',', ' '); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="total">
            Total: <span><?= number_format($commande['prix'], 2, ',', ' ').' TND'; ?></span>
        </div>

        <div class="footer">
            <p>Merci pour votre achat ! Contactez-nous à support@eshs.com pour toute question.</p>
            <div class="buttons">
                <a href="../index.php" class="back-btn">⬅ Retour à l'Accueil</a>
                <button class="btn-print" onclick="window.print();">Imprimer la Facture</button>
            </div>
        </div>
    </div>
</body>
</html>