<?php
require_once '../config.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($nom) || empty($email) || empty($password)) {
        header("Location: ../css/inscription.html?error=Veuillez remplir tous les champs obligatoires");
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../css/inscription.html?error=Email invalide");
        exit();
    }
    if (strlen($nom) > 50) {
        header("Location: ../css/inscription.html?error=Le nom ne peut pas dépasser 50 caractères");
        exit();
    }

    try {
        $database = new Database();
        $conn = $database->connect();

        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            header("Location: ../css/inscription.html?error=Cet email est déjà utilisé");
            exit();
        }

        // Handle photo upload
        $photo = 'default_photo.png';
        if (!empty($_FILES['photo']['name'])) {
            $file = $_FILES['photo'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB

            // Validate file
            if ($file['error'] !== UPLOAD_ERR_OK) {
                header("Location: ../css/inscription.html?error=Erreur lors du téléchargement de la photo : " . $file['error']);
                exit();
            }
            if (!in_array($file['type'], $allowed_types)) {
                header("Location: ../css/inscription.html?error=Type de fichier non autorisé. Utilisez JPEG, PNG ou GIF");
                exit();
            }
            if ($file['size'] > $max_size) {
                header("Location: ../css/inscription.html?error=La photo dépasse la taille maximale de 5 Mo");
                exit();
            }

            // Generate unique file name to avoid overwrites
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('profile_') . '.' . $ext;
            $target = "../css/assets/photos/" . $filename;

            // Ensure directory exists
            if (!is_dir('./assets/photos/')) {
                mkdir('./assets/photos/', 0755, true);
            }

            // Move file
            if (move_uploaded_file($file['tmp_name'], $target)) {
                $photo = $target; // Store the path to the photo in the database
            } else {
                error_log("Failed to move uploaded file to $target");
                header("Location: ../css/inscription.html?error=Impossible de sauvegarder la photo");
                exit();
            }
        }

        // Insert user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (nom, email, password, photo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $hashed_password, $photo]);

        // Redirect to login
        header("Location: ../authentification.html");
        exit();
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        header("Location: ../css/inscription.html?error=Erreur lors de l'inscription : " . htmlspecialchars($e->getMessage()));
        exit();
    }
} else {
    header("Location: ../css/inscription.html?error=Méthode de requête invalide");
    exit();
}
?>