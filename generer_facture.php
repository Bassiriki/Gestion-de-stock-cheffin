<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestion_stock";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

if (!isset($_POST['operation_id'])) {
    die("Erreur : operation_id non défini.");
}

$operation_id = $_POST['operation_id'];

$sql = "SELECT o.type_operation, o.quantite, o.prix_unitaire, o.prix_total, o.date_operation, p.nom 
        FROM operations o 
        JOIN produits p ON o.produit_id = p.id 
        WHERE o.id = $operation_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $type_operation = ucfirst($row['type_operation']);
    $quantite = $row['quantite'];
    $prix_unitaire = $row['prix_unitaire'];
    $prix_total = $row['prix_total'];
    $date_operation = $row['date_operation'];
    $nom_produit = $row['nom'];
} else {
    echo "Opération non trouvée.";
    $conn->close();
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Facture</h2>
    <table class="table table-bordered">
        <tr>
            <th>Produit</th>
            <td><?php echo htmlspecialchars($nom_produit); ?></td>
        </tr>
        <tr>
            <th>Quantité</th>
            <td><?php echo htmlspecialchars($quantite); ?></td>
        </tr>
        <tr>
            <th>Prix unitaire (CFA)</th>
            <td><?php echo htmlspecialchars($prix_unitaire); ?></td>
        </tr>
        <tr>
            <th>Prix total (CFA)</th>
            <td><?php echo htmlspecialchars($prix_total); ?></td>
        </tr>
        <tr>
            <th>Date de l'opération</th>
            <td><?php echo htmlspecialchars($date_operation); ?></td>
        </tr>
        <tr>
            <th>Type d'opération</th>
            <td><?php echo htmlspecialchars($type_operation); ?></td>
        </tr>
    </table>
    <div class="text-center mt-3">
        <a href="javascript:window.print()" class="btn btn-primary">Imprimer la Facture</a>
        <a href="inventaire.php" class="btn btn-secondary">Retour à l'inventaire</a>
    </div>
</div>
</body>
</html>
