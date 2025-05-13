<?php
session_start();
require_once '../config.php'; // Inclure la classe Database

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../css/login.php");
    exit();
}

$database = new Database();
$conn = $database->connect();

// Gestion du panier
$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];

// Nettoyer les anciennes données du panier (si elles sont au mauvais format)
foreach ($panier as $cart_key => $item) {
    if (!is_array($item) || !isset($item['produit_id'], $item['quantite'], $item['prix'])) {
        unset($panier[$cart_key]);
    }
}
$_SESSION['panier'] = $panier;

// Calcul du total du panier
$total = 0;
foreach ($panier as $cart_key => $item) {
    $total += $item['prix'] * $item['quantite'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier les informations de paiement
    $methode_paiement = htmlspecialchars(strip_tags($_POST['methode_paiement']));
    $numero_carte = $_POST['numero_carte'];
    $code_secret = $_POST['code_secret'];
    $adresse_livraison = htmlspecialchars(strip_tags($_POST['adresse_livraison']));

    // Validation des champs
    if (strlen($numero_carte) != 16 || !is_numeric($numero_carte)) {
        $error_message = "Le numéro de carte doit contenir 16 chiffres.";
    } elseif (strlen($code_secret) != 4 || !is_numeric($code_secret)) {
        $error_message = "Le code secret doit être composé de 4 chiffres.";
    } elseif (empty($panier)) {
        $error_message = "Votre panier est vide.";
    } else {
        try {
            // Sauvegarder la commande dans la base de données
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("INSERT INTO commande (user_id, prix, adresse_livraison, date_commande) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$user_id, $total, $adresse_livraison]);

            // Récupérer l'ID de la commande
            $commande_id = $conn->lastInsertId();

            // Sauvegarder les détails de la commande dans commande_produit
            foreach ($panier as $cart_key => $item) {
                $stmt = $conn->prepare("INSERT INTO commande_produit (commande_id, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
                $stmt->execute([$commande_id, $item['produit_id'], $item['quantite'], $item['prix']]);
            }

            // Réinitialiser le panier
            unset($_SESSION['panier']);

            // Redirection vers facture.php
            header("Location: ../css/facture.php?id=$commande_id&success=true");
            exit();
        } catch (PDOException $e) {
            $error_message = "Erreur lors de l'enregistrement de la commande : " . $e->getMessage();
        }
    }
}

// Si un message d'erreur existe, afficher l'alerte
if (isset($error_message)) {
    echo "<script>alert('" . htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - ESHS</title>
    <style>
        :root {
            --jaune-fonce: #FFB300; /* Jaune vibrant, moderne */
            --noir: #1A1A1A; /* Noir profond pour contraste */
            --blanc: #FFFFFF; /* Blanc pur pour clarté */
            --accent-jaune: #FFD700; /* Jaune clair pour accents */
            --gris: #E0E0E0; /* Gris clair pour éléments secondaires */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif; /* Police moderne et épurée */
            background: linear-gradient(135deg, var(--noir) 30%, var(--jaune-fonce) 100%);
            color: var(--noir);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://i.imgur.com/stadium-crowd-yellow-black.jpg') no-repeat center/cover;
            opacity: 0.15; /* Plus subtil */
            z-index: -1;
            filter: blur(8px);
        }

        .form-container {
            background: var(--blanc);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 0 0 15px rgba(255, 179, 0, 0.3);
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            position: relative;
            border: 2px solid var(--jaune-fonce);
        }

        h1 {
            color: var(--noir);
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            background: linear-gradient(to right, var(--jaune-fonce), var(--accent-jaune));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-transform: uppercase;
            position: relative;
        }

        h1::after {
            content: '⚽';
            position: absolute;
            top: -5px;
            right: -25px;
            font-size: 1.2rem;
            color: var(--jaune-fonce);
            opacity: 0.8;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--noir);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input {
            width: 100%;
            padding: 0.9rem;
            border: 1px solid var(--gris);
            border-radius: 8px;
            font-size: 1rem;
            background: var(--blanc);
            color: var(--noir);
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: var(--jaune-fonce);
            box-shadow: 0 0 0 3px rgba(255, 179, 0, 0.2);
            outline: none;
        }

        input::placeholder {
            color: #A0A0A0;
            font-style: italic;
        }

        button {
            background: linear-gradient(90deg, var(--jaune-fonce), var(--accent-jaune));
            color: var(--noir);
            border: none;
            padding: 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        button:hover {
            background: linear-gradient(90deg, var(--accent-jaune), var(--jaune-fonce));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 179, 0, 0.4);
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        .alert {
            background: #FFE082;
            color: var(--noir);
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
        }

        @media (max-width: 480px) {
            .form-container {
                padding: 1.5rem;
                max-width: 90%;
            }

            h1 {
                font-size: 1.5rem;
            }

            input, button {
                font-size: 0.9rem;
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Formulaire de paiement</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="methode_paiement">Méthode de paiement</label>
                <input type="text" id="methode_paiement" name="methode_paiement" placeholder="Ex. Visa, MasterCard" required>
            </div>
            <div class="form-group">
                <label for="numero_carte">Numéro de carte (16 chiffres)</label>
                <input type="text" id="numero_carte" name="numero_carte" placeholder="1234 5678 9012 3456" required maxlength="16" pattern="\d{16}">
            </div>
            <div class="form-group">
                <label for="code_secret">Code secret (4 chiffres)</label>
                <input type="password" id="code_secret" name="code_secret" placeholder="****" required maxlength="4" pattern="\d{4}">
            </div>
            <div class="form-group">
                <label for="adresse_livraison">Adresse de livraison</label>
                <input type="text" id="adresse_livraison" name="adresse_livraison" placeholder="Ex. 123 Rue du Stade, Paris" required>
            </div>
            <button type="submit">Confirmer le paiement</button>
        </form>
    </div>
</body>
</html>