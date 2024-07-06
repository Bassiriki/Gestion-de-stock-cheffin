<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Conteneur</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

<style>
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
    .nav-link-index {
            background-color: #007bff; /* Bleu */
        }
    .nav-link-liste_conteneurs {
            background-color: #ffc107; /* Orange */
            }
            body{
            background-color:#dcdcdc
        }
    </style>
    </head>
    <body>
        
    
<div class="row justify-content-center mt-4">
    <div class="col-lg-10">
    <div class="nav-links-container">
        <a class="nav-link nav-link-liste_conteneurs" href="liste_conteneurs.php"><i class="fas fa-warehouse"></i> Liste Conteneurs</a>
        <a class="nav-link nav-link-index" href="index.php"><i class="fas fa-cogs"></i> Accueil</a>
                    
    </div>
    </div>
<body>
    <div class="container mt-5">
        <h2>Ajouter un Conteneur</h2>
        <form action="contenaire.php" method="POST">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="quantite">Quantité</label>
                <input type="number" class="form-control" id="quantite" name="quantite" required>
            </div>
            <div class="form-group">
                <label for="prix_unitaire">Prix Unitaire</label>
                <input type="number" step="0.01" class="form-control" id="prix_unitaire" name="prix_unitaire" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
    </body>
      
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'];
        $quantite = $_POST['quantite'];
        $prix_unitaire = $_POST['prix_unitaire'];
        $montant_total = $quantite * $prix_unitaire;

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "gestion_stock";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO conteneurs (nom, quantite, prix_unitaire) VALUES ('$nom', $quantite, $prix_unitaire)";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-4'>Nouveau conteneur ajouté avec succès!</div>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>
</body>
</html>
