<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur ou l'administrateur est connecté
if (!isset($_SESSION['utilisateur_id']) && !isset($_SESSION['admin_id'])) {
    echo "Utilisateur non connecté.";
    exit();
}

// Déterminez si c'est un utilisateur ou un administrateur
$is_user = isset($_SESSION['utilisateur_id']);
$utilisateur_id = $is_user ? $_SESSION['utilisateur_id'] : $_SESSION['admin_id'];

// Récupérer les demandes d'amis pour l'utilisateur ou l'administrateur connecté
$sql = "SELECT * FROM demandes_amis WHERE destinataire_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $utilisateur_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sender_id = $row['demandeur_id'];
        
        // Rechercher dans les deux tables utilisateurs et administrateurs
        $user_query = "SELECT pseudo FROM utilisateurs WHERE id = ? UNION SELECT pseudo FROM administrateurs WHERE id = ?";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bind_param("ii", $sender_id, $sender_id);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();
        
        if ($user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
            echo "<p>Demande d'ami de : " . htmlspecialchars($user['pseudo']) . "</p>";
            echo "<form method='post' action='accept_friend.php'>
                    <input type='hidden' name='sender_id' value='$sender_id'>
                    <input type='submit' name='accept_friend' value='Accepter'>
                  </form>";
            echo "<form method='post' action='decline_friend.php'>
                    <input type='hidden' name='sender_id' value='$sender_id'>
                    <input type='submit' name='decline_friend' value='Refuser'>
                  </form>";
        } else {
            echo "Erreur lors de la récupération de l'utilisateur.";
        }
    }
} else {
    echo "Aucune demande d'ami.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un ami</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #3b5998; /* Couleur de fond similaire au logo */
            color: #fff; /* Texte blanc pour contraste */
        }
        .navbar {
            background-color: #3b5998; /* Couleur de la barre de navigation */
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-nav .nav-link {
            color: #3b5998 !important; /* Couleur du texte de navigation */
        }
        .navbar-text {
            color: #3b5998; /* Couleur du texte "Connecté en tant que" */
        }
        .btn-primary {
            background-color: #4267B2; /* Couleur bleue primaire pour les boutons */
            border-color: #4267B2;
        }
        .btn-secondary {
            background-color: #8b9dc3; /* Couleur secondaire pour les boutons */
            border-color: #8b9dc3;
        }
        .carousel-inner img {
            width: 100%;
            height: auto;
        }
        .profile-img-nav {
            height: 30px;
            width: 30px;
            border-radius: 50%;
            margin-left: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
            <img src="LOGOfecebook.jpg" alt="Logo FECEBOOK" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="mon_reseau.php">Mon Réseau</a></li>
                <li class="nav-item"><a class="nav-link" href="vous.php">Vous</a></li>
                <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
                <li class="nav-item"><a class="nav-link" href="messagerie.php">Messagerie</a></li>
                <li class="nav-item"><a class="nav-link" href="emplois.php">Emplois</a></li>
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="admin_only.php">ADMIN ONLY</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        Connecté en tant que <?= htmlspecialchars($_SESSION['pseudo'] ?? 'Utilisateur') ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Se déconnecter</a>
                </li>
            </ul>
        </div>
    </nav>
    
</body>
</html>
