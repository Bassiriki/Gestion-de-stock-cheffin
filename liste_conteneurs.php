<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Conteneurs</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .text-left {
            text-align: left;
        }
        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .container {
            margin-top: 50px;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
            border-collapse: collapse;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .table-bordered th {
            background-color: #f8f9fa;
            color: #495057;
        }
        .table-bordered td {
            background-color: #fff;
            color: #000;
        }
        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="text-left mb-3">
        <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
    <div class="container">
        <h2>Liste des Conteneurs</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Quantité</th>
                    <th>Prix Unitaire</th>
                    <th>Montant Total</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Configuration de la connexion à la base de données
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "gestion_stock";

                // Création de la connexion
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Vérification de la connexion
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Suppression d'un conteneur si l'ID de suppression est spécifié
                if (isset($_GET['delete_id'])) {
                    $delete_id = $_GET['delete_id'];
                    $delete_sql = "DELETE FROM conteneurs WHERE id = $delete_id";
                    $conn->query($delete_sql);
                }

                // Insertion d'un nouveau conteneur avec la date actuelle
                if (isset($_POST['submit'])) {
                    $nom = $_POST['nom'];
                    $quantite = $_POST['quantite'];
                    $prix_unitaire = $_POST['prix_unitaire'];

                    // Requête d'insertion avec NOW() pour la date actuelle
                    $sql_insert = "INSERT INTO conteneurs (nom, quantite, prix_unitaire, date) VALUES ('$nom', $quantite, $prix_unitaire, NOW())";

                    if ($conn->query($sql_insert) === TRUE) {
                        echo "Nouveau conteneur ajouté avec succès.";
                    } else {
                        echo "Erreur lors de l'ajout du conteneur : " . $conn->error;
                    }
                }

                // Récupération des données des conteneurs depuis la base de données
                $sql = "SELECT *, quantite * prix_unitaire AS montant_total FROM conteneurs";
                $result = $conn->query($sql);

                // Affichage des conteneurs dans le tableau
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nom'] . "</td>";
                        echo "<td>" . $row['quantite'] . "</td>";
                        echo "<td>" . $row['prix_unitaire'] . "</td>";
                        echo "<td>" . $row['montant_total'] . "</td>";
                        // Conversion de la date au format français (exemple : 01-01-2024)
                        $date_fr = date_format(date_create($row['date']), 'd-m-Y');
                        echo "<td>" . $date_fr . "</td>";
                        echo "<td><a href='liste_conteneurs.php?delete_id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Supprimer</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Aucun conteneur trouvé</td></tr>";
                }

                // Fermeture de la connexion à la base de données
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
