<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheffin - Gestion de Stock</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        /* Styles spécifiques */
        .container-fluid {
            padding: 20px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        .nav-link {
            display: inline-block;
            padding: 15px 20px;
            border-radius: 8px;
            color: #fff; /* Couleur du texte */
            transition: background-color 0.3s ease;
            text-decoration: none;
            font-size: 18px;
            text-align: center;
        }
        .nav-link:hover {
            text-decoration: none;
        }
        .nav-link-home {
            background-color: #007bff; /* Bleu */
        }
        .nav-link-inventaire {
            background-color: #ffc107; /* Orange */
        }
        .nav-link-operation {
            background-color: #28a745; /* Vert */
        }
        .nav-link-dettes {
            background-color: #6c757d; /* Gris foncé */
        }
        .nav-link-produit {
            background-color: #dc3545; /* Rouge */
        }
        .nav-link-contenaire {
            background-color: #007bff; /* Bleu */
        }
        .nav-links-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .nav-link i {
            margin-right: 8px;
        }
        .carousel-item img {
            width: 100%;
            height: auto;
            border-radius: 8px; /* Ajout de coins arrondis aux images */
        }
    </style>
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Cheffin - Gestion de Stock</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="inventaire.php"><i class="fas fa-warehouse"></i> Inventaire</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="operation.php"><i class="fas fa-cogs"></i> Opérations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dettes.php"><i class="fas fa-hand-holding-usd"></i> Dettes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ajouter_produit.php"><i class="fas fa-plus-circle"></i> Ajouter un produit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contenaire.php"><i class="fas fa-plus-circle"></i> Ajouter un conteneur</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="mb-4">Bienvenue sur Cheffin - Gestion de Stock</h2>
                <p class="lead">Votre solution pour une gestion efficace du stock et des dettes.</p>
            </div>
        </div>

        <!-- Liens en bloc horizontal -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-10">
                <div class="nav-links-container">
                    <a class="nav-link nav-link-inventaire" href="inventaire.php"><i class="fas fa-warehouse"></i> Inventaire</a>
                    <a class="nav-link nav-link-operation" href="operation.php"><i class="fas fa-cogs"></i> Opérations</a>
                    <a class="nav-link nav-link-dettes" href="dettes.php"><i class="fas fa-hand-holding-usd"></i> Dettes</a>
                    <a class="nav-link nav-link-produit" href="ajouter_produit.php"><i class="fas fa-plus-circle"></i> Ajouter un produit</a>
                    <a class="nav-link nav-link-contenaire" href="contenaire.php"><i class="fas fa-plus-circle"></i> Ajouter un conteneur</a>
                </div>
            </div>
        </div>

        <!-- Carrousel de photos -->
        <div id="carouselExampleIndicators" class="carousel slide mt-4" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="d-block w-100" src="assets/photo1.jpg" alt="First slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="assets/photo2.jpg" alt="Second slide">
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="assets/photo3.jpg" alt="Third slide">
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
    </div>

    <!-- Pied de page -->
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Cheffin - Bassiriki Mangane Ingenieur logiciel +221 77 838 51 24</p>
    </div>

    <!-- Scripts JavaScript requis par Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
