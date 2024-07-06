<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Produit</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background-color:#dcdcdc
        }
    </style>
</head>
<body>
    <div class="container mt-5">
    <div class="text-right mb-3">
        <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
    <h2>Ajouter un Produit</h2>
    <?php
    include 'db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $quantite = $_POST['quantite'];
        $prix = $_POST['prix'];

        $sql = "INSERT INTO produits (nom, quantite, prix) VALUES ('$nom', $quantite, $prix)";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>Produit ajouté avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
    ?>

    <form method="post" action="">
        <div class="form-group">
            <label for="nom">Nom du Produit:</label>
            <input type="text" name="nom" class="form-control" id="nom" required>
        </div>
        <div class="form-group">
            <label for="quantite">Quantité:</label>
            <input type="number" name="quantite" class="form-control" id="quantiter" required>
        </div>
        <div class="form-group">
            <label for="prix">Prix:</label>
            <input type="number" step="0.01" name="prix" class="form-control" id="prix" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter Produit</button>
    </form>
</div>
</body>
</html>
