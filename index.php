<?php
session_start();
include 'connexion.php';

// Vérifiez si l'utilisateur ou l'administrateur est connecté
if (!isset($_SESSION['utilisateur_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Déterminez si c'est un utilisateur ou un administrateur
$is_user = isset($_SESSION['utilisateur_id']);
$utilisateur_id = $is_user ? $_SESSION['utilisateur_id'] : $_SESSION['admin_id'];

// Récupérer les albums des amis de l'utilisateur ou de l'administrateur
$query = "SELECT a.id as album_id, a.photo, a.legende, u.pseudo, u.photo_profil,
          (SELECT COUNT(*) FROM like_photo WHERE photo_id = a.id) as like_count,
          (SELECT COUNT(*) FROM like_photo WHERE photo_id = a.id AND utilisateur_id = ?) as user_liked
          FROM albums a
          JOIN utilisateurs u ON a.utilisateur_id = u.id
          JOIN amis am ON (am.user_id = u.id OR am.friend_id = u.id)
          WHERE (am.user_id = ? OR am.friend_id = ?)
          AND u.id != ?
          GROUP BY a.id";
$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $utilisateur_id, $utilisateur_id, $utilisateur_id, $utilisateur_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - FECEBOOK</title>
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
        .album-photo {
            background-color: #fff;
            color: #3b5998;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .album-photo img {
            width: 100%;
            height: auto;
            max-height: 100%;
        }
        .comment-section {
            margin-top: 20px;
        }
        .comment {
            margin-bottom: 10px;
        }
        .profile-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .profile-info img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }
        .job-posting {
            background-color: #fff;
            color: #3b5998;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
        }
        .ece-info {
            background-color: #fff;
            color: #3b5998;
            border-radius: 10px;
            padding: 20px;
            margin-top: 40px;
        }
        .ece-info h3 {
            margin-bottom: 20px;
        }
        .ece-info a {
            color: #3b5998;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
            <img src="LOGOfecebook.jpg" alt="Logo FECEBOOK">
        </a>
        <div class="meta-logo">
            <img src="meta.jpg" alt="Meta Logo" style="height: 20px; margin-left: 10px;">
        </div>
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
        <h1>Bienvenue sur FECEBOOK</h1>

        <!-- Carrousel -->
        <div id="carouselExampleIndicators" class="carousel slide mb-5" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="image11.jpg" class="d-block w-100" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="image22.jpg" class="d-block w-100" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="image33.jpg" class="d-block w-100" alt="Slide 3">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <!-- Section Histoire de l'ECE -->
        <div class="text-center mb-5">
            <h2>Histoire de l'ECE Paris</h2>
            <p>
                L'ECE Paris est une grande école d'ingénieurs généraliste fondée en 1919. 
                Située au cœur de la capitale française, elle forme des ingénieurs dans 
                les domaines des technologies de l'information, de l'électronique, des 
                télécommunications et des systèmes embarqués. Avec un fort accent sur 
                l'innovation et l'entrepreneuriat, l'ECE Paris prépare ses étudiants à 
                relever les défis technologiques de demain.
                <br>Contactez nous au 01 44 39 06 00, 
                <br>10 Rue Sextius Michel, 75015 Paris
            </p>
        </div>

        <h2>Fil d'actualité</h2>
        <div class="row">
            <div class="col-md-8">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="album-photo mb-4">
                        <div class="profile-info">
                            <img src="<?= htmlspecialchars($row['photo_profil']) ?>" alt="Photo de profil">
                            <strong><?= htmlspecialchars($row['pseudo']) ?></strong>
                        </div>
                        <img src="<?= htmlspecialchars($row['photo']) ?>" alt="Photo de l'album" class="w-100 mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="like-count"><?= htmlspecialchars($row['like_count']) ?> likes</span>
                            <span class="like-btn" data-photo-id="<?= $row['album_id'] ?>">
                                <?php if ($row['user_liked']): ?>
                                    ❤️ Unlike
                                <?php else: ?>
                                    ♡ Like
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="comment-section">
                            <h5>Commentaires</h5>
                            <?php if (!empty($row['legende'])): ?>
                                <div class="comment">
                                    <strong>Légende:</strong>
                                    <p><?= htmlspecialchars($row['legende']) ?></p>
                                </div>
                            <?php endif; ?>
                            <?php
                            // Récupérer les commentaires pour cette photo
                            $query_comments = "SELECT c.commentaire, c.date_commentaire, u.pseudo 
                                               FROM commentaires c 
                                               JOIN utilisateurs u ON c.utilisateur_id = u.id 
                                               WHERE c.photo_id = ? 
                                               ORDER BY c.date_commentaire DESC";
                            $stmt_comments = $conn->prepare($query_comments);
                            $stmt_comments->bind_param("i", $row['album_id']);
                            $stmt_comments->execute();
                            $result_comments = $stmt_comments->get_result();
                            ?>
                            <div class="comments-list">
                                <?php while ($comment = $result_comments->fetch_assoc()): ?>
                                    <div class="comment">
                                        <strong><?= htmlspecialchars($comment['pseudo']) ?>:</strong>
                                        <p><?= htmlspecialchars($comment['commentaire']) ?></p>
                                        <small><?= htmlspecialchars($comment['date_commentaire']) ?></small>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            
                            <!-- Formulaire d'ajout de commentaire -->
                            <form action="add_comment.php" method="post">
                                <input type="hidden" name="photo_id" value="<?= $row['album_id'] ?>">
                                <div class="form-group">
                                    <label for="commentaire">Votre commentaire:</label>
                                    <textarea class="form-control" name="commentaire" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Commenter</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="col-md-4">
                <h3>Offres d'emploi</h3>
                <?php
                $query_jobs = "SELECT titre, description, entreprise, localisation, date_publication 
                               FROM emplois 
                               ORDER BY date_publication DESC 
                               LIMIT 5";
                $result_jobs = $conn->query($query_jobs);

                while ($job = $result_jobs->fetch_assoc()): ?>
                    <div class="job-posting">
                        <h4><?= htmlspecialchars($job['titre']) ?></h4>
                        <p><?= htmlspecialchars($job['description']) ?></p>
                        <p><strong>Entreprise:</strong> <?= htmlspecialchars($job['entreprise']) ?></p>
                        <p><strong>Localisation:</strong> <?= htmlspecialchars($job['localisation']) ?></p>
                        <p><strong>Date de publication:</strong> <?= htmlspecialchars($job['date_publication']) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        
        <!-- Section Informations ECE -->
        <div class="ece-info mt-5">
            <h3>Informations sur l'ECE Paris</h3>
            <p>
                Pour plus d'informations sur l'ECE Paris, visitez notre 
                <a href="https://www.ece.fr/" target="_blank">site officiel</a>.
            </p>
            <p>
                Adresse: 10 Rue Sextius Michel, 75015 Paris
                <br>Téléphone: 01 44 39 06 00
                <br>Email: contact@ece.fr
            </p>
            <p>
                <a href="https://www.google.com/maps/place/ECE+-+Ecole+d'ing%C3%A9nieurs+de+la+Ville+de+Paris/@48.8493263,2.2903392,17z/data=!3m1!4b1!4m5!3m4!1s0x47e671d7d0b3d15d:0x5f478bc00f97d0ed!8m2!3d48.8493263!4d2.2925279" target="_blank">Voir sur Google Maps</a>
            </p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.like-btn').click(function() {
                var $btn = $(this);
                var photoId = $btn.data('photo-id');
                $.ajax({
                    url: 'like_photo.php',
                    method: 'POST',
                    data: { photo_id: photoId },
                    success: function(response) {
                        if (response === 'liked') {
                            $btn.html('❤️ Unlike');
                            var likeCount = parseInt($btn.siblings('.like-count').text());
                            $btn.siblings('.like-count').text(likeCount + 1 + ' likes');
                        } else if (response === 'unliked') {
                            $btn.html('♡ Like');
                            var likeCount = parseInt($btn.siblings('.like-count').text());
                            $btn.siblings('.like-count').text(likeCount - 1 + ' likes');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
