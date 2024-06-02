<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$is_user = isset($_SESSION['utilisateur_id']);
$is_admin = isset($_SESSION['admin_id']);

// Récupérer les informations de l'utilisateur connecté
if ($is_user) {
    $utilisateur_id = $_SESSION['utilisateur_id'];
} elseif ($is_admin) {
    $admin_id = $_SESSION['admin_id'];
}

// Gérer la soumission du formulaire pour ajouter un nouvel album
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $photos = $_FILES['photos'];

    for ($i = 0; $i < count($photos['name']); $i++) {
        $photo_name = $photos['name'][$i];
        $photo_tmp_name = $photos['tmp_name'][$i];
        $photo_error = $photos['error'][$i];
        $photo_size = $photos['size'][$i];

        if ($photo_error === 0) {
            $photo_ext = pathinfo($photo_name, PATHINFO_EXTENSION);
            $photo_new_name = uniqid('', true) . "." . $photo_ext;
            $photo_destination = 'uploads/' . $photo_new_name;
            move_uploaded_file($photo_tmp_name, $photo_destination);

            // Insérer la photo dans la base de données
            if ($is_user) {
                $query_insert_photo = "INSERT INTO albums (utilisateur_id, photo) VALUES (?, ?)";
                $stmt_insert_photo = $conn->prepare($query_insert_photo);
                $stmt_insert_photo->bind_param("is", $utilisateur_id, $photo_destination);
                $stmt_insert_photo->execute();

                // Ajouter une notification pour le nouvel album photo
                $notification_query = "INSERT INTO notifications (type, date, utilisateur_id, photo_id) VALUES ('album', NOW(), ?, ?)";
                $stmt_notification = $conn->prepare($notification_query);
                $stmt_notification->bind_param("ii", $utilisateur_id, $conn->insert_id);
                $stmt_notification->execute();
            } elseif ($is_admin) {
                $query_insert_photo = "INSERT INTO albums (admin_id, photo) VALUES (?, ?)";
                $stmt_insert_photo = $conn->prepare($query_insert_photo);
                $stmt_insert_photo->bind_param("is", $admin_id, $photo_destination);
                $stmt_insert_photo->execute();

                // Ajouter une notification pour le nouvel album photo
                $notification_query = "INSERT INTO notifications (type, date, utilisateur_id, photo_id) VALUES ('album', NOW(), ?, ?)";
                $stmt_notification = $conn->prepare($notification_query);
                $stmt_notification->bind_param("ii", $admin_id, $conn->insert_id);
                $stmt_notification->execute();
            }
        }
    }

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Album Photo - FECEBOOK</title>
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
        .container {
            background-color: #fff;
            color: #000;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
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
    <div class="container mt-3">
        <h1>Ajouter un Album Photo</h1>
        <form action="add_album.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="photos">Sélectionner des photos :</label>
                <input type="file" class="form-control-file" id="photos" name="photos[]" multiple required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
