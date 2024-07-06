<?php
// Inclusion du fichier de connexion à la base de données
include 'db.php';

// Initialisation des variables
$type_operations = isset($_POST['type_operation']) ? $_POST['type_operation'] : array();
$produit_noms = isset($_POST['produit_nom']) ? $_POST['produit_nom'] : array();
$quantites = isset($_POST['quantite']) ? $_POST['quantite'] : array();
$prix_unitaire = isset($_POST['prix_unitaire']) ? $_POST['prix_unitaire'] : '';

// Traitement du formulaire d'enregistrement d'opération
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification de l'existence des données nécessaires
    if (empty($type_operations) || empty($produit_noms) || empty($quantites) || empty($prix_unitaire)) {
        echo "<div class='alert alert-danger'>Tous les champs sont obligatoires.</div>";
    } else {
        // Validation et traitement des données
        foreach ($type_operations as $key => $type_operation) {
            if (empty($produit_noms[$key]) || empty($quantites[$key])) {
                echo "<div class='alert alert-danger'>Tous les champs sont obligatoires.</div>";
                continue; // Passer à l'itération suivante si des champs obligatoires sont manquants
            }

            // Récupérer les données du formulaire
            $produit_nom = $produit_noms[$key];
            $quantite = $quantites[$key];
            $prix_total = $quantite * $prix_unitaire;

            // Récupérer l'ID du produit
            $sql_produit = "SELECT id FROM produits WHERE nom='$produit_nom'";
            $result = $conn->query($sql_produit);

            if ($result->num_rows > 0) {
                $produit = $result->fetch_assoc();
                $produit_id = $produit['id'];

                // Vérifier la quantité disponible pour une sortie
                if ($type_operation == 'sortie') {
                    $sql_quantite_disponible = "SELECT quantite FROM produits WHERE id = $produit_id";
                    $result_disponible = $conn->query($sql_quantite_disponible);

                    if ($result_disponible->num_rows > 0) {
                        $row = $result_disponible->fetch_assoc();
                        $quantite_disponible = $row['quantite'];

                        // Vérifier la quantité disponible
                        if ($quantite > $quantite_disponible) {
                            echo "<div class='alert alert-danger'>Erreur : La quantité demandée ($quantite) dépasse la quantité disponible ($quantite_disponible) pour $produit_nom.</div>";
                            continue; // Passer à l'itération suivante si la quantité est insuffisante
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Erreur lors de la récupération de la quantité disponible pour $produit_nom.</div>";
                        continue; // Passer à l'itération suivante en cas d'erreur
                    }
                }

                // Insertion de l'opération dans la table 'operations'
                $sql_insert = "INSERT INTO operations (type_operation, produit_id, quantite, prix_unitaire, prix_total, date_operation) 
                               VALUES ('$type_operation', $produit_id, $quantite, $prix_unitaire, $prix_total, NOW())";

                if ($conn->query($sql_insert) === TRUE) {
                    echo "<div class='alert alert-success'>Opération enregistrée avec succès.</div>";

                    // Mise à jour de la quantité du produit dans la table 'produits'
                    if ($type_operation == 'entree' || $type_operation == 'retour') {
                        $sql_update = "UPDATE produits SET quantite = quantite + $quantite WHERE id = $produit_id";
                    } elseif ($type_operation == 'sortie') {
                        $sql_update = "UPDATE produits SET quantite = quantite - $quantite WHERE id = $produit_id";
                    }

                    if ($conn->query($sql_update) !== TRUE) {
                        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour de la quantité du produit : " . $conn->error . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Erreur lors de l'enregistrement de l'opération : " . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Produit non trouvé.</div>";
            }
        }
    }
}

// Récupération des noms des produits pour la liste déroulante
$sql = "SELECT nom FROM produits";
$result = $conn->query($sql);
$options = "";
while ($row = $result->fetch_assoc()) {
    $nom_produit = $row['nom'];
    $options .= "<option value='$nom_produit'>$nom_produit</option>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Opérations</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-check {
            display: inline-block;
            padding: 15px 20px;
            border-radius: 8px;
            color: #fff; /* Couleur du texte */
            transition: background-color 0.3s ease;
            text-decoration: none;
            font-size: 18px;
            text-align: center;
            border: 1px solid #dee2e6; /* Bordure grise légère */
            padding: 10px; /* Espacement interne */
            margin-bottom: 10px; /* Marge en bas */
        }
        body{
            background-color:#dcdcdc
        }
        
        .entree { background-color: #ffeeba;
            
         }
        .sortie { background-color: #c3e6cb; }
    </style>
</head>
<body>
    

<div class="container mt-5">
    <div class="text-right mb-3">
        <a href="index.php" class="btn btn-secondary">Accueil</a>
    </div>
    <div class="text-left mb-3">
        <a href="liste_operations.php" class="btn btn-secondary">Listes des operations</a>
    </div>
    <h2>Enregistrer une Opération</h2>
    
    <!-- Formulaire d'enregistrement d'opération -->
    <form method="post" action="" class="mb-3">
        <div class="form-group">
            <label>Type d'Opération:</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="type_operation[]" id="entree" value="entree">
                <label class="form-check-label" for="entree" style="font-weight: bold; color: #007bff;  ">Entrée</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="type_operation[]" id="sortie" value="sortie">
                <label class="form-check-label" for="sortie" style="font-weight: bold; color: #28a745;">Sortie</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="type_operation[]" id="retour" value="sortie">
                <label class="form-check-label" for="retour" style="font-weight: bold; color:  #007bff;">retour</label>
            </div>
        </div>
        <div class="form-group">
            <label for="produit_nom">Produit:</label><br>
            <select name="produit_nom[]" class="form-control" id="produit_nom" multiple>
                <?php echo $options; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantite">Quantité:</label><br>
            <input type="number" name="quantite[]" class="form-control" id="quantite" required>
        </div>
        <div class="form-group">
            <label for="prix_unitaire">Prix unitaire:</label><br>
            <input type="number" step="0.01" name="prix_unitaire" class="form-control" id="prix_unitaire" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer Opération</button>
    </form>
</div>
</body>
</html>
