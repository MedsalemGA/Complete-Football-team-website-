<?php
session_start();

require_once '../config.php'; // Inclure la classe Database

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../css/login.php");
    exit();
}

// Initialiser les variables de message
$success = '';
$error = '';

// Connexion à la base de données
$database = new Database();
$conn = $database->connect();

// Récupérer les informations de l'utilisateur connecté
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT nom, email, photo FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Si l'utilisateur existe, afficher ses informations
if ($user) {
    $nom = $user['nom'];
    $email = $user['email'];
    $profile_picture = $user['photo'] ? '../assets/' . htmlspecialchars($user['photo']) : '../assets/default_photo.png';
} else {
    echo "Erreur : Utilisateur non trouvé.";
    exit();
}

// Logique pour modifier les informations ou le mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        // Exemple : Validation du mot de passe
        if (empty($_POST['new_password'])) {
            $error = 'Le mot de passe ne peut pas être vide.';
        } else {
            // Mise à jour réussie
            $success = 'Le mot de passe a été mis à jour avec succès.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - ESHS</title>
    <link rel="stylesheet" href="../css/moncompte.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bienvenue, <?php echo htmlspecialchars($nom); ?></h1>
        </div>

        <div class="profile-picture">
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Photo de profil" style="max-width: 150px; border-radius: 50%;">
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="user-info">
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($nom); ?></p>
            <p><strong>Email :</strong> <?php echo htmlspecialchars($email); ?></p>
        </div>

        <div class="buttons">
            <a href="../pages/modifier_compte.php" class="btn">Modifier mon compte</a>
            <a href="../css/logout.php" class="btn">Se déconnecter</a>
        </div>
    </div>
</body>
</html>