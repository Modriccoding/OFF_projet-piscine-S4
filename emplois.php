<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur ou l'administrateur est connecté
if (!isset($_SESSION['utilisateur_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$is_user = isset($_SESSION['utilisateur_id']);
$is_admin = isset($_SESSION['admin_id']);

// Récupérer les informations de l'utilisateur ou de l'administrateur connecté
if ($is_user) {
    $utilisateur_id = $_SESSION['utilisateur_id'];
    $query_user = "SELECT pseudo FROM utilisateurs WHERE id = ?";
    $stmt_user = $conn->prepare($query_user);
    $stmt_user->bind_param("i", $utilisateur_id);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    $utilisateur = $result_user->fetch_assoc();
    $pseudo = isset($utilisateur['pseudo']) ? htmlspecialchars($utilisateur['pseudo']) : 'Utilisateur inconnu';
} elseif ($is_admin) {
    $admin_id = $_SESSION['admin_id'];
    $query_admin = "SELECT pseudo FROM administrateurs WHERE id = ?";
    $stmt_admin = $conn->prepare($query_admin);
    $stmt_admin->bind_param("i", $admin_id);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();
    $admin = $result_admin->fetch_assoc();
    $pseudo = isset($admin['pseudo']) ? htmlspecialchars($admin['pseudo']) : 'Administrateur inconnu';
}

// Gérer la soumission du formulaire pour ajouter une nouvelle offre d'emploi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $is_admin) {
    if (isset($_POST['titre']) && isset($_POST['description'])) {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        
        $query_insert_job = "INSERT INTO emplois (titre, description) VALUES (?, ?)";
        $stmt_insert_job = $conn->prepare($query_insert_job);
        $stmt_insert_job->bind_param("ss", $titre, $description);
        $stmt_insert_job->execute();
        
        // Ajouter une notification pour la nouvelle offre d'emploi
        $notification_query = "INSERT INTO notifications (type, date, utilisateur_id) VALUES ('job', NOW(), ?)";
        $stmt_notification = $conn->prepare($notification_query);
        $stmt_notification->bind_param("i", $admin_id);
        $stmt_notification->execute();
        
        header("Location: emplois.php");
        exit();
    } elseif (isset($_POST['delete_job_id'])) {
        $delete_job_id = $_POST['delete_job_id'];
        
        $query_delete_job = "DELETE FROM emplois WHERE id = ?";
        $stmt_delete_job = $conn->prepare($query_delete_job);
        $stmt_delete_job->bind_param("i", $delete_job_id);
        $stmt_delete_job->execute();
        
        // Supprimer les notifications associées à l'offre d'emploi
        $notification_delete_query = "DELETE FROM notifications WHERE type = 'job' AND utilisateur_id = ?";
        $stmt_notification_delete = $conn->prepare($notification_delete_query);
        $stmt_notification_delete->bind_param("i", $delete_job_id);
        $stmt_notification_delete->execute();
        
        header("Location: emplois.php");
        exit();
    }
}

// Récupérer les offres d'emploi
$query_jobs = "SELECT * FROM emplois";
$result_jobs = $conn->query($query_jobs);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres d'emploi - FECEBOOK</title>
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
        .container {
            background-color: #fff;
            color: #000;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .table th, .table td {
            color: #000;
        }
        .footer {
            background-color: #3b5998;
            color: #fff;
            padding: 10px 0;
            text-align: center;
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
                <?php if ($is_admin): ?>
                    <li class="nav-item"><a class="nav-link" href="admin_only.php">ADMIN ONLY</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="navbar-text">
                        Connecté en tant que <?= $pseudo ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Se déconnecter</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-3">
        <h1>Offres d'emploi</h1>
        
        <?php if ($is_admin): ?>
            <!-- Section pour ajouter une nouvelle offre d'emploi (visible uniquement pour les administrateurs) -->
            <h2>Ajouter une nouvelle offre d'emploi</h2>
            <form action="emplois.php" method="post">
                <div class="form-group">
                    <label for="titre">Titre :</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>
                <div class="form-group">
                    <label for="description">Description :</label>
                    <textarea class="form-control" id="description" name="description" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Publier</button>
            </form>
        <?php endif; ?>
        
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Action</th>
                    <?php if ($is_admin): ?>
                        <th>Supprimer</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_jobs->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['titre']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><a href="postuler.php?id=<?= $row['id'] ?>" class="btn btn-primary">Postuler</a></td>
                        <?php if ($is_admin): ?>
                            <td>
                                <form action="emplois.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">
                                    <input type="hidden" name="delete_job_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <footer class="footer mt-3">
        <p>&copy; 2024 ECE In - Tous droits réservés</p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
