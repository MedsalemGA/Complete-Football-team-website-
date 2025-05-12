<?php
session_start();

require_once '../config.php'; // Database configuration

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

if ($user) {
    $nom = $user['nom'];
    $email = $user['email'];
    $photo = $user['photo'] ?? 'default_photo.png';
} else {
    echo "Erreur : Utilisateur non trouvé.";
    exit();
}

// Logique pour modifier les informations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_name = trim($_POST['nom']);
    $new_email = trim($_POST['email']);
    $new_password = $_POST['password'];
    $confirmpassword = $_POST['confirm_password'];

    // Validation
    if (empty($new_name) || empty($new_email)) {
        $error = 'Le nom et l\'email ne peuvent pas être vides.';
    } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'L\'email n\'est pas valide.';
    } elseif (strlen($new_name) > 50) {
        $error = 'Le nom ne peut pas dépasser 50 caractères.';
    } else {
        try {
            // Préparer la mise à jour
            $updates = [];
            $params = [];
            if ($new_name !== $user['nom']) {
                $updates[] = 'nom = ?';
                $params[] = $new_name;
            }
            if ($new_email !== $user['email']) {
                $updates[] = 'email = ?';
                $params[] = $new_email;
            }
            if (!empty($new_password)) {
                if ($new_password === $confirmpassword) {
                    $updates[] = 'password = ?';
                    $params[] = password_hash($new_password, PASSWORD_DEFAULT);
                } else {
                    $error = 'Les mots de passe ne correspondent pas.';
                }
            }

            // Gestion de l'upload de la photo
            if (!empty($_FILES['photo']['name'])) {
                $file = $_FILES['photo'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 2 * 1024 * 1024; // 2MB
                $upload_dir = '../assets/photos/';
                $file_name = time() . '_' . basename($file['name']); // Unique file name
                $target = $upload_dir . $file_name;

                // Vérifier le répertoire
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                // Validation du fichier
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $error = 'Erreur lors du téléchargement de l\'image.';
                } elseif (!in_array($file['type'], $allowed_types)) {
                    $error = 'Type de fichier non autorisé. Utilisez JPEG, PNG ou GIF.';
                } elseif ($file['size'] > $max_size) {
                    $error = 'L\'image est trop volumineuse. Taille maximale : 2 Mo.';
                } elseif (!move_uploaded_file($file['tmp_name'], $target)) {
                    $error = 'Échec du téléchargement de l\'image. Vérifiez les permissions du dossier.';
                } else {
                    $updates[] = 'photo = ?';
                    $params[] = 'photos/' . $file_name; // Stocker le chemin relatif
                }
            }

            if (!empty($updates) && empty($error)) {
                $params[] = $user_id;
                $query = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->execute($params);

                // Mettre à jour la session
                if ($new_name !== $user['nom']) {
                    $_SESSION['user_nom'] = $new_name;
                }
                $success = 'Vos informations ont été mises à jour avec succès.';
            }
        } catch (PDOException $e) {
            $error = 'Erreur lors de la mise à jour : ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESHS | Modifier mon compte</title>
    <link rel="stylesheet" href="../css/modifiercompte.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
</head>
<body>
    <!-- NAVBAR -->
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
            <li><a href="Billets.html">Billets</a></li>
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
                        <li><a href="../mon-compte.php">Mon compte</a></li>
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
                <li><a href="Billets.html">Tickets</a></li>
                <li><a href="Boutique.php">Boutique</a></li>
                <li><a href="news.php">News</a></li>
                <li><a href="services.php">Services</a></li>
            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="main-container">
        <!-- Form Section -->
        <div class="form-container">
            <h1>Modifier mon compte</h1>
            <div class="profile-photo">
                <img src="../assets/<?php echo htmlspecialchars($photo); ?>" alt="Photo de profil" style="max-width: 150px; border-radius: 50%;">
            </div>
            
            <!-- Affichage des messages -->
            <?php if (!empty($success)): ?>
                <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form id="update-form" method="POST" enctype="multipart/form-data">
                <div class="form-section">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required aria-label="Nom">
                </div>
                <div class="form-section">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required aria-label="Email">
                </div>
                <div class="form-section">
                    <label for="password">Nouveau mot de passe (facultatif)</label>
                    <input type="password" id="password" name="password" placeholder="Nouveau mot de passe" aria-label="Nouveau mot de passe">
                </div>
                <div class="form-section">
                    <label for="confirm_password">Confirmer le mot de passe</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe" aria-label	beginning_of_output="Confirmer le mot de passe">
                </div>
                <div class="form-section">
                    <label for="photo">Photo de profil</label>
                    <input type="file" id="photo" name="photo" accept="image/*" aria-label="Photo de profil">
                </div>
                <button type="submit">Enregistrer les modifications</button>
            </form>
            <div id="loading" class="loading"></div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="sidebar">
            <div class="widget">
                <h3>Mot des Fans</h3>
                <p class="fan-quote">"ESHS, notre fierté ! Toujours derrière vous !" - Ali, supporter fidèle</p>
                <p class="fan-quote">"Le stade Bouali Lahwar, c'est notre maison !" - Nour, fan depuis 2010</p>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
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

    <!-- SCRIPTS -->
    <script src="../js/modifiercompte.js"></script>
</body>
</html>
