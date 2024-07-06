<?php
// Inclusion du fichier de connexion à la base de données
include 'db.php';

// Suppression d'une opération
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['operation_id'])) {
    $operation_id = $_POST['operation_id'];

    // Récupérer les détails de l'opération avant la suppression
    $sql_operation = "SELECT * FROM operations WHERE id = $operation_id";
    $result_operation = $conn->query($sql_operation);

    if ($result_operation->num_rows > 0) {
        $operation = $result_operation->fetch_assoc();
        $produit_id = $operation['produit_id'];
        $quantite = $operation['quantite'];
        $type_operation = $operation['type_operation'];

        // Mettre à jour la quantité disponible en fonction du type d'opération
        if ($type_operation == 'entree') {
            $sql_update = "UPDATE produits SET quantite = quantite - $quantite WHERE id = $produit_id";
            if ($conn->query($sql_update) !== TRUE) {
                echo "<div class='alert alert-danger'>Erreur lors de la mise à jour de la quantité du produit : " . $conn->error . "</div>";
            }
        } elseif ($type_operation == 'sortie') {
            $sql_update = "UPDATE produits SET quantite = quantite + $quantite WHERE id = $produit_id";
            if ($conn->query($sql_update) !== TRUE) {
                echo "<div class='alert alert-danger'>Erreur lors de la mise à jour de la quantité du produit : " . $conn->error . "</div>";
            }
        }

        // Supprimer l'opération
        $sql_delete = "DELETE FROM operations WHERE id = $operation_id";
        if ($conn->query($sql_delete) === TRUE) {
            echo "<div class='alert alert-success'>Opération supprimée avec succès.</div>";
        } else {
            echo "<div class='alert alert-danger'>Erreur lors de la suppression de l'opération : " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Opération non trouvée.</div>";
    }
}

// Récupération des opérations
$sql_operations = "SELECT o.id, o.type_operation, p.nom AS produit_nom, o.quantite, o.prix_unitaire, o.prix_total, o.date_operation 
                   FROM operations o 
                   JOIN produits p ON o.produit_id = p.id 
                   ORDER BY o.date_operation DESC";
$result_operations = $conn->query($sql_operations);

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Opérations</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background-color:#dcdcdc
        }
        .entree { background-color: lightgreen; }
        .sortie { background-color: lightcoral; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="text-right mb-3">
        <a href="inventaire.php" class="btn btn-secondary">Retour à l'inventaire</a>
    </div>
    <div class="text-left mb-3">
        <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
    <h2>Liste des Opérations</h2>
    
    <?php
    if ($result_operations->num_rows > 0) {
        while ($row = $result_operations->fetch_assoc()) {
            $operation_id = $row['id'];
            $type_operation = ucfirst($row['type_operation']);
            $nom_produit = $row['produit_nom'];
            $quantite = $row['quantite'];
            $prix_unitaire = $row['prix_unitaire'];
            $prix_total = $row['prix_total'];
            $date_operation = $row['date_operation'];

            // Appliquer une classe CSS en fonction du type d'opération
            $operation_class = ($type_operation == 'Entree') ? 'entree' : 'sortie';

            echo "<div class='card mb-3 $operation_class'>";
            echo "<div class='card-header'>$type_operation</div>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>$nom_produit</h5>";
            echo "<p class='card-text'>Quantité: $quantite<br>Prix unitaire: $prix_unitaire<br>Prix total: $prix_total<br>Date: $date_operation</p>";
            echo "<form method='post' action='' style='display:inline;'>
                    <input type='hidden' name='operation_id' value='$operation_id'>
                    <button type='submit' class='btn btn-sm btn-danger'>Supprimer</button>
                  </form>";
            echo "<form method='post' action='generer_facture.php' style='display:inline; margin-left:10px;'>
                    <input type='hidden' name='operation_id' value='$operation_id'>
                    <button type='submit' class='btn btn-sm btn-primary'>Imprimer Facture</button>
                  </form>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Aucune opération enregistrée.</div>";
    }
    ?>

</div>
</body>
</html>
