<?php
include 'db.php';

// Suppression d'une opération
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['operation_id'])) {
    $operation_id = $_POST['operation_id'];

    $sql_delete = "DELETE FROM operations WHERE id = $operation_id";

    if ($conn->query($sql_delete) === TRUE) {
        // Suppression réussie, retourner un message ou rediriger
        echo "<div class='alert alert-success'>Opération supprimée avec succès.</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la suppression de l'opération : " . $conn->error . "</div>";
    }
}

// Récupération des opérations
$sql_operations = "SELECT o.id, o.type_operation, p.nom AS produit_nom, o.quantite, o.prix_unitaire, o.prix_total, o.date_operation 
                   FROM operations o 
                   JOIN produits p ON o.produit_id = p.id 
                   ORDER BY o.date_operation DESC";
$result_operations = $conn->query($sql_operations);

// Affichage des opérations
if ($result_operations->num_rows > 0) {
    while ($row = $result_operations->fetch_assoc()) {
        // Affichage des données de l'opération
        // Vous pouvez afficher ici les données sous forme de tableau HTML, par exemple
    }
} else {
    echo "Aucune opération trouvée.";
}

$conn->close();
?>
