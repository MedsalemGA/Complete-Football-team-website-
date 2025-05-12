<?php
session_start();
require_once '../config.php'; // Inclure la classe Database

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../css/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - ESHS</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/services.css?v=1">
</head>
<body class="bg-gray-900 text-white font-montserrat flex flex-col min-h-screen">
    <!-- Header (from index.php, matching Billets.php) -->
    <nav class="navbar bg-gradient-to-r from-black to-gray-800 shadow-lg p-4 flex items-center justify-between">
        <div class="logo">
            <a href="../index.php">
                <img src="../assets/ES_Hammam-Sousse_logo.png" alt="Logo" class="h-12">
            </a>
        </div>
        <p class="text-yellow-400 text-sm">Sponsorisé par</p>
        <div class="sponsor">
            <a href="#"><img src="../assets/Capture d'écran 2025-03-01 011926.png" alt="Sponsor" class="h-10"></a>
        </div>
        <ul class="link1 flex space-x-6 text-yellow-400 font-semibold hidden md:flex">
            <li><a href="../index.php">Home</a></li>
            <li><a href="Billets.php">Billets</a></li>
            <li><a href="Boutique.php">Boutique de Fans</a></li>
            <li><a href="ESHStv.php">ESHS TV</a></li>
            <li><a href="adehsion.php">Histoire</a></li>
            <li><a href="services.php">Services</a></li>
        </ul>
        <ul class="links flex items-center space-x-4">
            <?php if (isset($_SESSION['user_nom'])): ?>
                <li class="user-menu relative">
                    <span class="user-name cursor-pointer text-yellow-400"><?php echo htmlspecialchars($_SESSION['user_nom']); ?> ▼</span>
                    <ul class="dropdown-user absolute hidden bg-black border-2 border-yellow-400 rounded-md mt-2 right-0">
                        <li><a href="../pages/mon-compte.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Mon compte</a></li>
                        <li><a href="../css/logout.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Déconnexion</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="../authentification.php"><img src="../assets/2354573.png" alt="Login" class="h-6"></a></li>
            <?php endif; ?>
            <li><img src="../assets/iconmonstr-line-three-horizontal-lined-removebg-preview.png" alt="Menu" id="menuIcon" class="h-6 cursor-pointer md:hidden"></li>
        </ul>
        <div class="menu-container md:hidden">
            <ul id="dropdownMenu" class="dropdown-menu hidden absolute bg-black border-2 border-yellow-400 rounded-md w-48 right-4 top-16">
                <li><a href="../index.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Accueil</a></li>
                <li><a href="../pages/mon-compte.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Mon compte</a></li>
                <li><a href="Billets.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Billets</a></li>
                <li><a href="Boutique.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Boutique</a></li>
                <li><a href="news.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">News</a></li>
                <li><a href="services.php" class="block px-4 py-2 text-yellow-400 hover:bg-gray-800">Services</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content: Chatbot -->
    <main class="flex-grow flex items-center justify-center p-4">
        <div class="chatbot-container bg-gray-800 rounded-lg shadow-2xl w-full max-w-lg border-2 border-yellow-400">
            <div class="chatbot-header bg-yellow-400 text-black font-bold text-lg p-4 rounded-t-lg">
                ESHS Chatbot
            </div>
            <div class="chatbot-messages p-4 h-96 overflow-y-auto" id="chatbotMessages">
                <div class="message bot-message bg-gray-700 text-white p-3 rounded-lg mb-2 slide-in">
                    Bonjour ! Je suis le chatbot ESHS. Posez-moi vos questions sur les billets, ESHS TV, la boutique, ou autres services !
                </div>
            </div>
            <div class="chatbot-input flex p-4 border-t border-gray-600">
                <input type="text" id="userInput" class="flex-grow bg-gray-700 text-white rounded-l-lg p-2 outline-none" placeholder="Tapez votre question..." />
                <button id="sendButton" class="bg-yellow-400 text-black font-semibold p-2 rounded-r-lg hover:bg-yellow-500">Envoyer</button>
            </div>
        </div>
    </main>

    <!-- Footer (from index.php, matching Billets.php) -->
    <footer class="bg-gradient-to-r from-black to-gray-800 text-center py-8">
        <div class="footer-content">
            <h3 class="text-yellow-400 text-xl font-semibold mb-4">Suivez-nous sur les réseaux sociaux</h3>
            <ul class="flex justify-center space-x-4">
                <li><a href="https://www.instagram.com/espoir.sportif.hammam.sousse/"><img src="../assets/pngtree-instagram-social-platform-icon-png-image_6315976-removebg-preview.png" alt="Instagram" class="h-10"></a></li>
                <li><a href="https://www.facebook.com/profile.php?id=100030548511719"><img src="../assets/png-clipart-facebook-logo-computer-icons-facebook-logo-facebook-thumbnail-removebg-preview.png" alt="Facebook" class="h-10"></a></li>
                <li><a href="https://mail.google.com/mail/u/0/#inbox"><img src="../assets/png-transparent-mail-google-gmail-google-s-logo-icon-removebg-preview.png" alt="Email" class="h-10"></a></li>
                <li><a href="https://www.youtube.com/@EspoirSportifDeHammamSousse/videos"><img src="../assets/free-youtube-logo-icon-2431-thumb-removebg-preview.png" alt="YouTube" class="h-10"></a></li>
            </ul>
        </div>
        <div class="bas flex justify-center space-x-4 my-4">
            <span><a href="https://www.ftf.org.tn/fr/"><img src="../assets/Logo_federation_tunisienne_de_football.svg-removebg-preview.png" alt="FTF" class="h-12"></a></span>
            <span><img src="../assets/images__1_-removebg-preview.png" alt="Ligue 2" class="h-12"></span>
        </div>
        <div class="footer-bottom">
            <p class="text-yellow-400">Allez ESHS ! ⚽ © 2025 Espoir Sportif Hammam-Sousse. Tous droits réservés</p>
        </div>
    </footer>

    <script>
        // Consolidated initialization
        document.addEventListener('DOMContentLoaded', function() {
            try {
                // Chatbot elements
                const chatbotMessages = document.getElementById('chatbotMessages');
                const userInput = document.getElementById('userInput');
                const sendButton = document.getElementById('sendButton');

                // Verify elements
                if (!chatbotMessages || !userInput || !sendButton) {
                    console.error('Chatbot elements not found:', {
                        chatbotMessages: !!chatbotMessages,
                        userInput: !!userInput,
                        sendButton: !!sendButton
                    });
                    return;
                }

                // Chatbot responses
                const responses = {
                    // Questions sur les billets
                    "comment acheter un billet": "Pour acheter un billet, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "je veux acheter un billet": "Pour acheter un billet, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "acheter billet": "Pour acheter un billet, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "achat billet": "Pour acheter un billet, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "obtenir un billet": "Pour acheter un billet, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "réserver une place": "Pour réserver une place, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "les billets": "Pour acheter un billet, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "billets match": "Pour acheter un billet, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "ticket match": "Pour acheter un billet, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "tickets": "Pour acheter un billet, connectez-vous à votre compte, allez à la page 'Billets', sélectionnez le match, choisissez votre place, et payez en ligne. Vous recevrez un QR code par email !",
                    "prix des billets": "Les prix des billets varient selon le match et la catégorie (Standard ou VIP). Consultez la page 'Billets' pour voir les tarifs actuels de chaque rencontre.",
                    "tarif billet": "Les prix des billets varient selon le match et la catégorie (Standard ou VIP). Consultez la page 'Billets' pour voir les tarifs actuels de chaque rencontre.",
                    "combien coûte un billet": "Les prix des billets varient selon le match et la catégorie (Standard ou VIP). Consultez la page 'Billets' pour voir les tarifs actuels de chaque rencontre.",
                    "prix ticket": "Les prix des billets varient selon le match et la catégorie (Standard ou VIP). Consultez la page 'Billets' pour voir les tarifs actuels de chaque rencontre.",
                    "tarif entrée": "Les prix des billets varient selon le match et la catégorie (Standard ou VIP). Consultez la page 'Billets' pour voir les tarifs actuels de chaque rencontre.",
                    "réduction billets": "Des réductions sont disponibles pour les membres du club et les groupes de plus de 10 personnes. Contactez-nous à billetterie@eshs.tn pour plus d'informations.",
                    "tarif réduit": "Des réductions sont disponibles pour les membres du club et les groupes de plus de 10 personnes. Contactez-nous à billetterie@eshs.tn pour plus d'informations.",
                    "billet moins cher": "Des réductions sont disponibles pour les membres du club et les groupes de plus de 10 personnes. Contactez-nous à billetterie@eshs.tn pour plus d'informations.",
                    "promotion billet": "Des réductions sont disponibles pour les membres du club et les groupes de plus de 10 personnes. Contactez-nous à billetterie@eshs.tn pour plus d'informations.",
                    "réduction étudiant": "Des réductions sont disponibles pour les membres du club et les groupes de plus de 10 personnes. Contactez-nous à billetterie@eshs.tn pour plus d'informations.",
                    "remboursement billet": "Les billets peuvent être remboursés jusqu'à 48h avant le match. Contactez-nous à billetterie@eshs.tn avec votre numéro de commande.",
                    "annuler billet": "Les billets peuvent être remboursés jusqu'à 48h avant le match. Contactez-nous à billetterie@eshs.tn avec votre numéro de commande.",
                    "annulation ticket": "Les billets peuvent être remboursés jusqu'à 48h avant le match. Contactez-nous à billetterie@eshs.tn avec votre numéro de commande.",
                    "remboursement ticket": "Les billets peuvent être remboursés jusqu'à 48h avant le match. Contactez-nous à billetterie@eshs.tn avec votre numéro de commande.",
                    "où sont mes billets": "Vos billets électroniques sont envoyés par email et également disponibles dans la section 'Mes billets' de votre compte personnel.",
                    "trouver mes billets": "Vos billets électroniques sont envoyés par email et également disponibles dans la section 'Mes billets' de votre compte personnel.",
                    "récupérer billet": "Vos billets électroniques sont envoyés par email et également disponibles dans la section 'Mes billets' de votre compte personnel.",
                    "mes tickets": "Vos billets électroniques sont envoyés par email et également disponibles dans la section 'Mes billets' de votre compte personnel.",
                    "billet électronique": "Vos billets électroniques sont envoyés par email et également disponibles dans la section 'Mes billets' de votre compte personnel.",
                    // Questions sur ESHS TV
                    "eshs tv": "ESHS TV propose des vidéos exclusives comme des résumés de matchs et des entraînements. Visitez la page 'ESHS TV' pour regarder !",
                    "eshstv": "ESHS TV propose des vidéos exclusives comme des résumés de matchs et des entraînements. Visitez la page 'ESHS TV' pour regarder !",
                    "tv eshs": "ESHS TV propose des vidéos exclusives comme des résumés de matchs et des entraînements. Visitez la page 'ESHS TV' pour regarder !",
                    "chaîne tv": "ESHS TV propose des vidéos exclusives comme des résumés de matchs et des entraînements. Visitez la page 'ESHS TV' pour regarder !",
                    "télévision": "ESHS TV propose des vidéos exclusives comme des résumés de matchs et des entraînements. Visitez la page 'ESHS TV' pour regarder !",
                    "vidéos": "Retrouvez toutes nos vidéos sur la page 'ESHS TV' : résumés de matchs, interviews des joueurs, coulisses du club et bien plus encore !",
                    "voir vidéos": "Retrouvez toutes nos vidéos sur la page 'ESHS TV' : résumés de matchs, interviews des joueurs, coulisses du club et bien plus encore !",
                    "vidéo match": "Retrouvez toutes nos vidéos sur la page 'ESHS TV' : résumés de matchs, interviews des joueurs, coulisses du club et bien plus encore !",
                    "clips": "Retrouvez toutes nos vidéos sur la page 'ESHS TV' : résumés de matchs, interviews des joueurs, coulisses du club et bien plus encore !",
                    "contenu vidéo": "Retrouvez toutes nos vidéos sur la page 'ESHS TV' : résumés de matchs, interviews des joueurs, coulisses du club et bien plus encore !",
                    "match en direct": "Les matchs en direct sont parfois diffusés sur notre page 'ESHS TV'. Suivez-nous sur les réseaux sociaux pour être informé des prochaines diffusions.",
                    "direct match": "Les matchs en direct sont parfois diffusés sur notre page 'ESHS TV'. Suivez-nous sur les réseaux sociaux pour être informé des prochaines diffusions.",
                    "streaming": "Les matchs en direct sont parfois diffusés sur notre page 'ESHS TV'. Suivez-nous sur les réseaux sociaux pour être informé des prochaines diffusions.",
                    "diffusion en direct": "Les matchs en direct sont parfois diffusés sur notre page 'ESHS TV'. Suivez-nous sur les réseaux sociaux pour être informé des prochaines diffusions.",
                    "live": "Les matchs en direct sont parfois diffusés sur notre page 'ESHS TV'. Suivez-nous sur les réseaux sociaux pour être informé des prochaines diffusions.",
                    "regarder match": "Les matchs en direct sont parfois diffusés sur notre page 'ESHS TV'. Suivez-nous sur les réseaux sociaux pour être informé des prochaines diffusions.",
                    "replay": "Les replays de nos matchs sont disponibles sur la page 'ESHS TV' généralement 24h après la fin de la rencontre.",
                    "revoir match": "Les replays de nos matchs sont disponibles sur la page 'ESHS TV' généralement 24h après la fin de la rencontre.",
                    "match passé": "Les replays de nos matchs sont disponibles sur la page 'ESHS TV' généralement 24h après la fin de la rencontre.",
                    "rediffusion": "Les replays de nos matchs sont disponibles sur la page 'ESHS TV' généralement 24h après la fin de la rencontre.",
                    "voir ancien match": "Les replays de nos matchs sont disponibles sur la page 'ESHS TV' généralement 24h après la fin de la rencontre.",
                    "interviews": "Les interviews exclusives avec nos joueurs et notre staff sont disponibles sur la page 'ESHS TV'.",
                    "interview joueur": "Les interviews exclusives avec nos joueurs et notre staff sont disponibles sur la page 'ESHS TV'.",
                    "entretien": "Les interviews exclusives avec nos joueurs et notre staff sont disponibles sur la page 'ESHS TV'.",
                    "parole joueur": "Les interviews exclusives avec nos joueurs et notre staff sont disponibles sur la page 'ESHS TV'.",
                    "déclaration": "Les interviews exclusives avec nos joueurs et notre staff sont disponibles sur la page 'ESHS TV'.",
                    // Questions sur la boutique
                    "boutique": "La boutique de fans offre des maillots, écharpes, et autres articles ESHS. Consultez la page 'Boutique de Fans' pour commander.",
                    "la boutique": "La boutique de fans offre des maillots, écharpes, et autres articles ESHS. Consultez la page 'Boutique de Fans' pour commander.",
                    "voir boutique": "La boutique de fans offre des maillots, écharpes, et autres articles ESHS. Consultez la page 'Boutique de Fans' pour commander.",
                    "shop": "La boutique de fans offre des maillots, écharpes, et autres articles ESHS. Consultez la page 'Boutique de Fans' pour commander.",
                    "magasin": "La boutique de fans offre des maillots, écharpes, et autres articles ESHS. Consultez la page 'Boutique de Fans' pour commander.",
                    "acheter produits": "La boutique de fans offre des maillots, écharpes, et autres articles ESHS. Consultez la page 'Boutique de Fans' pour commander.",
                    "fan shop": "La boutique de fans offre des maillots, écharpes, et autres articles ESHS. Consultez la page 'Boutique de Fans' pour commander.",
                    "articles club": "La boutique de fans offre des maillots, écharpes, et autres articles ESHS. Consultez la page 'Boutique de Fans' pour commander.",
                    "maillot": "Les maillots officiels de la saison sont disponibles dans notre boutique en ligne. Nous proposons les versions domicile, extérieur et third, en tailles adulte et enfant.",
                    "maillots": "Les maillots officiels de la saison sont disponibles dans notre boutique en ligne. Nous proposons les versions domicile, extérieur et third, en tailles adulte et enfant.",
                    "jersey": "Les maillots officiels de la saison sont disponibles dans notre boutique en ligne. Nous proposons les versions domicile, extérieur et third, en tailles adulte et enfant.",
                    "tenue": "Les maillots officiels de la saison sont disponibles dans notre boutique en ligne. Nous proposons les versions domicile, extérieur et third, en tailles adulte et enfant.",
                    "maillot officiel": "Les maillots officiels de la saison sont disponibles dans notre boutique en ligne. Nous proposons les versions domicile, extérieur et third, en tailles adulte et enfant.",
                    "acheter maillot": "Les maillots officiels de la saison sont disponibles dans notre boutique en ligne. Nous proposons les versions domicile, extérieur et third, en tailles adulte et enfant.",
                    "produits dérivés": "Notre boutique propose une large gamme de produits dérivés : écharpes, casquettes, ballons, mugs et bien d'autres articles aux couleurs du club.",
                    "merchandising": "Notre boutique propose une large gamme de produits dérivés : écharpes, casquettes, ballons, mugs et bien d'autres articles aux couleurs du club.",
                    "goodies": "Notre boutique propose une large gamme de produits dérivés : écharpes, casquettes, ballons, mugs et bien d'autres articles aux couleurs du club.",
                    "souvenirs": "Notre boutique propose une large gamme de produits dérivés : écharpes, casquettes, ballons, mugs et bien d'autres articles aux couleurs du club.",
                    "articles supporters": "Notre boutique propose une large gamme de produits dérivés : écharpes, casquettes, ballons, mugs et bien d'autres articles aux couleurs du club.",
                    "accessoires": "Notre boutique propose une large gamme de produits dérivés : écharpes, casquettes, ballons, mugs et bien d'autres articles aux couleurs du club.",
                    "livraison": "La livraison est offerte pour toute commande supérieure à 100 DT. Les délais de livraison sont généralement de 3 à 5 jours ouvrables.",
                    "frais de livraison": "La livraison est offerte pour toute commande supérieure à 100 DT. Les délais de livraison sont généralement de 3 à 5 jours ouvrables.",
                    "délai livraison": "La livraison est offerte pour toute commande supérieure à 100 DT. Les délais de livraison sont généralement de 3 à 5 jours ouvrables.",
                    "livrer commande": "La livraison est offerte pour toute commande supérieure à 100 DT. Les délais de livraison sont généralement de 3 à 5 jours ouvrables.",
                    "expédition": "La livraison est offerte pour toute commande supérieure à 100 DT. Les délais de livraison sont généralement de 3 à 5 jours ouvrables.",
                    "recevoir commande": "La livraison est offerte pour toute commande supérieure à 100 DT. Les délais de livraison sont généralement de 3 à 5 jours ouvrables.",
                    "retour produit": "Vous pouvez retourner un produit dans les 14 jours suivant sa réception. Consultez notre politique de retour sur la page 'Boutique'.",
                    "retourner article": "Vous pouvez retourner un produit dans les 14 jours suivant sa réception. Consultez notre politique de retour sur la page 'Boutique'.",
                    "échange produit": "Vous pouvez retourner un produit dans les 14 jours suivant sa réception. Consultez notre politique de retour sur la page 'Boutique'.",
                    "remboursement article": "Vous pouvez retourner un produit dans les 14 jours suivant sa réception. Consultez notre politique de retour sur la page 'Boutique'.",
                    "produit défectueux": "Vous pouvez retourner un produit dans les 14 jours suivant sa réception. Consultez notre politique de retour sur la page 'Boutique'.",
                    "taille maillot": "Vous trouverez un guide des tailles sur la page de chaque produit dans notre boutique en ligne.",
                    "guide taille": "Vous trouverez un guide des tailles sur la page de chaque produit dans notre boutique en ligne.",
                    "quelle taille": "Vous trouverez un guide des tailles sur la page de chaque produit dans notre boutique en ligne.",
                    "taille vêtement": "Vous trouverez un guide des tailles sur la page de chaque produit dans notre boutique en ligne.",
                    // Questions sur l'histoire du club
                    "histoire": "Découvrez l'histoire de l'Espoir Sportif Hammam-Sousse sur la page 'Histoire'.",
                    "création du club": "L'Espoir Sportif Hammam-Sousse a été fondé en 1958. Vous pouvez en apprendre plus sur nos débuts sur la page 'Histoire'.",
                    "palmarès": "Notre palmarès comprend plusieurs titres régionaux et des participations en Ligue 1. Consultez la page 'Histoire' pour tous les détails.",
                    "joueurs célèbres": "Plusieurs joueurs célèbres ont porté nos couleurs. Découvrez-les sur notre page 'Histoire'.",
                    "stade": "Nous jouons nos matchs à domicile au stade Bouali Lahwar à Hammam-Sousse. Capacité : environ 6000 spectateurs.",
                    "local de club": "Stade Bouali Lahwar près de Menchia Hammam Sousse",
                    // Questions sur les services
                    "services": "Vous êtes sur la page des services ! Posez-moi une question sur nos offres ou utilisez ce chatbot pour obtenir de l'aide.",
                    "abonnement": "Les abonnements pour la saison sont disponibles sur la page 'Boutique de fans'. Ils vous permettent d'assister à tous les matchs à domicile à prix réduit.",
                    "devenir membre": "Pour le moment, nous n'avons pas cette option sur notre site web. Pour devenir membre, rendez-vous au local du club où vous obtiendrez toutes les informations nécessaires.",
                    "école de foot": "Notre école de football accueille les jeunes de 6 à 18 ans. Pour plus d'informations, rendez-vous au local du club.",
                    "stage vacances": "Nous organisons des stages pendant les vacances scolaires pour les jeunes de 8 à 16 ans. Les dates sont annoncées sur notre page d'accueil.",
                    // Questions sur le compte utilisateur
                    "mon compte": "Gérez votre profil sur la page 'Mon compte' après vous être connecté.",
                    "creer un compte": "Pour créer un compte, cliquez sur l'icône de connexion en haut à droite et sélectionnez 'Inscription'.",
                    "mot de passe oublié": "Si vous avez oublié votre mot de passe, cliquez sur 'Mot de passe oublié' sur la page de connexion et suivez les instructions.",
                    "modifier profil": "Pour modifier votre profil, connectez-vous et accédez à la page 'Mon compte', puis cliquez sur 'Modifier mon profil'.",
                    "supprimer compte": "Pour supprimer votre compte, contactez-nous à support@eshs.tn avec votre demande.",
                    // Questions sur la connexion/déconnexion
                    "connexion": "Pour vous connecter, cliquez sur l'icône de connexion en haut à droite ou visitez la page d'authentification.",
                    "déconnexion": "Pour vous déconnecter, cliquez sur votre nom dans la barre de navigation et sélectionnez 'Déconnexion'.",
                    "problème connexion": "Si vous rencontrez des problèmes de connexion, vérifiez vos identifiants ou utilisez l'option 'Mot de passe oublié'. Si le problème persiste, contactez-nous.",
                    // Questions sur les actualités
                    "actualités": "Retrouvez toutes nos actualités sur la page 'News'. Nous publions régulièrement des informations sur l'équipe, les matchs et la vie du club.",
                    "derniers résultats": "Les derniers résultats sont disponibles sur notre page d'accueil et dans la section 'News'.",
                    "calendrier": "Le calendrier des prochains matchs est disponible sur notre page d'accueil et dans la section 'Billets'.",
                    "transferts": "Les informations sur les transferts sont publiées dans notre section 'News' dès qu'elles sont officielles.",
                    // Questions sur le contact
                    "contact": "Vous pouvez nous contacter par email à contact@eshs.tn ou par téléphone au +216 73 123 456.",
                    "adresse": "Notre siège social est situé au Stade Bouali Lahwar, Avenue de la République, Hammam-Sousse, Tunisie.",
                    "réseaux sociaux": "Suivez-nous sur Facebook, Instagram, Twitter et YouTube. Les liens sont disponibles dans le pied de page de notre site.",
                    "newsletter": "Pour vous abonner à notre newsletter, entrez votre adresse email dans le formulaire en bas de notre page d'accueil.",
                    // Questions générales
                    "bonjour": "Bonjour ! Comment puis-je vous aider aujourd'hui ?",
                    "bonsoir": "Bonsoir ! Comment puis-je vous aider ?",
                    "merci": "Je vous en prie ! N'hésitez pas si vous avez d'autres questions.",
                    "au revoir": "Au revoir et merci de votre visite ! Allez ESHS !",
                    // Questions sur les matchs
                    "prochain match": "Les informations sur notre prochain match sont disponibles sur la page d'accueil. Vous pouvez également consulter le calendrier complet dans la section 'Billets'.",
                    "classement": "Notre position actuelle au classement est affichée sur la page d'accueil. Pour le classement complet, consultez la section 'News'.",
                    "joueurs blessés": "Les informations sur les joueurs blessés sont régulièrement mises à jour dans notre section 'News'.",
                    "composition équipe": "La composition de l'équipe pour le prochain match est généralement annoncée la veille sur nos réseaux sociaux et notre site.",
                    // Questions sur le parking et l'accès
                    "parking": "Un parking gratuit est disponible autour du stade Bouali Lahwar. Nous recommandons d'arriver au moins 1h avant le coup d'envoi pour trouver facilement une place.",
                    "accès stade": "Le stade est accessible en voiture et en transport en commun. Des navettes gratuites sont parfois mises en place depuis le centre-ville les jours de match.",
                    "pmr": "Des places spéciales et un accès adapté sont prévus pour les personnes à mobilité réduite. Contactez-nous à l'avance à billetterie@eshs.tn pour réserver.",
                    // Questions sur la restauration
                    "restauration stade": "Plusieurs points de restauration sont disponibles dans le stade, proposant sandwichs, boissons et snacks à des prix raisonnables.",
                    "buvette": "Des buvettes sont présentes dans chaque tribune du stade, proposant des boissons fraîches et des en-cas.",
                    // Questions sur les groupes de supporters
                    "supporters": "Plusieurs groupes de supporters animent nos matchs. Vous pouvez les rejoindre en les contactant via leurs pages sur les réseaux sociaux ou au stade.",
                    "ultra": "Notre principal groupe Ultra est 'Les Jaunes et Noirs'. Retrouvez-les sur Facebook pour plus d'informations.",
                    // Questions sur les enfants
                    "enfants stade": "Les enfants sont les bienvenus au stade ! Les mêmes tarifs que les adultes s'appliquent pour les moins de 18 ans et plus de 6 ans. Les enfants de moins de 6 ans entrent gratuitement.",
                    "activités enfants": "Des animations pour enfants sont parfois organisées avant les matchs. Consultez notre page d'accueil pour les annonces."
                };

                // Add message to chat
                function addMessage(text, isBot = false) {
                    try {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `message ${isBot ? 'bot-message bg-gray-700 text-white' : 'user-message bg-yellow-400 text-black'} p-3 rounded-lg mb-2 slide-in`;
                        messageDiv.textContent = text;
                        chatbotMessages.appendChild(messageDiv);
                        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
                    } catch (err) {
                        console.error('Error adding message:', err);
                    }
                }

                // Get response for question
                function getResponse(question) {
                    try {
                        const normalizedQuestion = question.toLowerCase().trim();
                        for (const key in responses) {
                            if (normalizedQuestion.includes(key)) {
                                return responses[key];
                            }
                        }
                        return "Désolé, je ne comprends pas votre question. Essayez de reformuler ou posez une question sur les billets, ESHS TV, la boutique, ou d'autres services du club !";
                    } catch (err) {
                        console.error('Error getting response:', err);
                        return "Une erreur s'est produite. Veuillez réessayer.";
                    }
                }

                // Event listeners for chatbot
                sendButton.addEventListener('click', function() {
                    try {
                        const question = userInput.value.trim();
                        if (question) {
                            addMessage(question, false);
                            const response = getResponse(question);
                            setTimeout(() => addMessage(response, true), 500);
                            userInput.value = '';
                        }
                    } catch (err) {
                        console.error('Error handling send button click:', err);
                    }
                });

                userInput.addEventListener('keypress', function(e) {
                    try {
                        if ((e.key === 'Enter' || e.keyCode === 13) && userInput.value.trim()) {
                            sendButton.click();
                        }
                    } catch (err) {
                        console.error('Error handling keypress:', err);
                    }
                });

                // Navbar event listeners
                const menuIcon = document.getElementById('menuIcon');
                const dropdownMenu = document.getElementById('dropdownMenu');
                if (menuIcon && dropdownMenu) {
                    menuIcon.addEventListener('click', function() {
                        dropdownMenu.classList.toggle('hidden');
                    });
                } else {
                    console.warn('Navbar elements not found:', {
                        menuIcon: !!menuIcon,
                        dropdownMenu: !!dropdownMenu
                    });
                }

                const userMenu = document.querySelector('.user-menu');
                if (userMenu) {
                    userMenu.addEventListener('click', function() {
                        const dropdown = userMenu.querySelector('.dropdown-user');
                        if (dropdown) {
                            dropdown.classList.toggle('hidden');
                        } else {
                            console.warn('User dropdown not found');
                        }
                    });
                } else {
                    console.warn('User menu not found');
                }

                console.log('Chatbot and navbar initialized successfully');
            } catch (err) {
                console.error('Initialization error:', err);
            }
        });
    </script>
</body>
</html>