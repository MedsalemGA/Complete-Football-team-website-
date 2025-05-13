<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "football_db";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    header("Location: ../authentification.html?error=Échec de connexion à la base de données");
    exit;
}

// Récupérer les données du formulaire
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];

// Vérifier si l'utilisateur existe
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_nom'] = $row['nom'];
        header("Location: ../index.php");
        exit;
    } else {
        header("Location: ../authentification.html?error=Mot de passe incorrect");
        exit;
    }
} else {
    header("Location: ../authentification.html?error=Aucun compte trouvé avec cet email");
    exit;
}

$conn->close();
?>
