<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur ou l'administrateur est connecté
if (!isset($_SESSION['utilisateur_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

// Récupérer l'ID de l'ami à partir de la requête GET
$friend_id = isset($_GET['friend_id']) ? intval($_GET['friend_id']) : 0;

if ($friend_id <= 0) {
    die("Ami invalide.");
}

// Déterminer si l'utilisateur ou l'administrateur est connecté
$is_user = isset($_SESSION['utilisateur_id']);
$is_admin = isset($_SESSION['admin_id']);

// Récupérer les informations de l'ami
$query_friend = "SELECT pseudo, email, nom, bio, photo_profil, photo_mur FROM utilisateurs WHERE id = ?";
$stmt_friend = $conn->prepare($query_friend);
if ($stmt_friend === false) {
    die("Erreur de préparation de la requête: " . $conn->error);
}
$stmt_friend->bind_param("i", $friend_id);
$stmt_friend->execute();
$result_friend = $stmt_friend->get_result();
$ami = $result_friend->fetch_assoc();

if (!$ami) {
    // Vérifier également dans la table administrateurs si l'ami n'est pas trouvé dans utilisateurs
    $query_friend = "SELECT pseudo, email, nom, bio, photo_profil, photo_mur FROM administrateurs WHERE id = ?";
    $stmt_friend = $conn->prepare($query_friend);
    if ($stmt_friend === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }
    $stmt_friend->bind_param("i", $friend_id);
    $stmt_friend->execute();
    $result_friend = $stmt_friend->get_result();
    $ami = $result_friend->fetch_assoc();
    
    if (!$ami) {
        die("Ami introuvable.");
    }

    // Récupérer les photos de l'album de l'ami administrateur
    $query_album = "SELECT photo FROM albums WHERE admin_id = ?";
    $stmt_album = $conn->prepare($query_album);
    if ($stmt_album === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }
    $stmt_album->bind_param("i", $friend_id);
} else {
    // Récupérer les photos de l'album de l'ami utilisateur
    $query_album = "SELECT photo FROM albums WHERE utilisateur_id = ?";
    $stmt_album = $conn->prepare($query_album);
    if ($stmt_album === false) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }
    $stmt_album->bind_param("i", $friend_id);
}
$stmt_album->execute();
$result_album = $stmt_album->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?= htmlspecialchars($ami['pseudo']) ?> - ECE In</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #3b5998;
            color: #fff;
        }
        .navbar {
            background-color: #3b5998;
        }
        .navbar-brand img {
            height: 40px;
        }
        .navbar-nav .nav-link {
            color: #3b5998 !important;
        }
        .navbar-text {
            color: #3b5998;
        }
        .profile-card {
            background-color: #fff;
            color: #3b5998;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        .profile-info {
            flex: 1;
            min-width: 250px;
        }
        .profile-img {
            max-width: 150px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .wall-img {
            flex: 1;
            max-width: 100%;
            border-radius: 10px;
            margin-top: 20px;
        }
        @media (min-width: 768px) {
            .wall-img {
                margin-top: 0;
                margin-left: 20px;
            }
        }
        .album {
            background-color: #fff;
            color: #3b5998;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .album h2 {
            margin-bottom: 20px;
        }
        .album-img {
            max-width: 100px;
            margin: 10px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
            <img src="LOGOfecebook.jpg" alt="Logo FECEBOOK">
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
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        Connecté en tant que <?= htmlspecialchars($_SESSION['pseudo']) ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Se déconnecter</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="profile-card">
            <div class="profile-info">
                <h1>Profil de <?= htmlspecialchars($ami['pseudo']) ?></h1>
                <?php if ($ami['photo_profil']): ?>
                    <img src="<?= htmlspecialchars($ami['photo_profil']) ?>" alt="Photo de profil" class="profile-img">
                <?php endif; ?>
                <p><strong>Email :</strong> <?= htmlspecialchars($ami['email']) ?></p>
                <p><strong>Nom :</strong> <?= htmlspecialchars($ami['nom']) ?></p>
                <p><strong>Bio :</strong> <?= htmlspecialchars($ami['bio'] ?? '') ?></p>
            </div>
            <?php if ($ami['photo_mur']): ?>
                <img src="<?= htmlspecialchars($ami['photo_mur']) ?>" alt="Image de mur" class="wall-img">
            <?php endif; ?>
        </div>
        <div class="album mt-5">
            <h2>Album Photos</h2>
            <div class="d-flex flex-wrap">
                <?php while ($row = $result_album->fetch_assoc()): ?>
                    <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Photo de l'album" class="album-img">
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
