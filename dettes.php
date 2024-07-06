<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Dettes</title>
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
    <h2>Gestion des Dettes</h2>
    <?php
    include 'db.php'; // Assurez-vous d'inclure votre fichier de connexion à la base de données ici

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['enregistrer'])) {
            // Vérifier si tous les champs requis sont remplis
            if (isset($_POST['produit_nom'], $_POST['description'], $_POST['quantite'], $_POST['prix_unitaire'])) {
                $produit_nom = $_POST['produit_nom'];
                $description = $_POST['description'];
                $quantite = $_POST['quantite'];
                $prix_unitaire = $_POST['prix_unitaire'];
                $prix_total = $quantite * $prix_unitaire;
                $montant_restant = $prix_total;

                // Récupérer les informations du produit
                $sql_produit = "SELECT id, quantite FROM produits WHERE nom='$produit_nom'";
                $result = $conn->query($sql_produit);
                if ($result && $result->num_rows > 0) {
                    $produit = $result->fetch_assoc();
                    $produit_id = $produit['id'];
                    $quantite_actuelle = $produit['quantite'];

                    // Vérifier si la quantité disponible est suffisante
                    if ($quantite > $quantite_actuelle) {
                        echo "<div class='alert alert-danger'>Erreur: Quantité insuffisante en stock pour enregistrer cette dette.</div>";
                    } else {
                        // Insérer la dette et mettre à jour la quantité du produit
                        $sql = "INSERT INTO dettes (produit_id, description, quantite, prix_unitaire, prix_total, montant_restant) VALUES ($produit_id, '$description', $quantite, $prix_unitaire, $prix_total, $montant_restant)";
                        if ($conn->query($sql) === TRUE) {
                            $sql_update = "UPDATE produits SET quantite = quantite - $quantite WHERE id = $produit_id";
                            if ($conn->query($sql_update) === TRUE) {
                                echo "<div class='alert alert-success'>Dette enregistrée avec succès et stock mis à jour.</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Erreur lors de la mise à jour du stock: " . $conn->error . "</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Erreur lors de l'enregistrement de la dette: " . $conn->error . "</div>";
                        }
                    }
                } else {
                    echo "<div class='alert alert-danger'>Erreur: Produit non trouvé.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Erreur: Tous les champs sont requis.</div>";
            }
        } elseif (isset($_POST['payer'])) {
            // Vérifier si tous les champs requis sont remplis
            if (isset($_POST['dette_id'], $_POST['montant_paye'])) {
                $dette_id = $_POST['dette_id'];
                $montant_paye = $_POST['montant_paye'];

                $sql_dette = "SELECT produit_id, montant_restant, quantite FROM dettes WHERE id = $dette_id";
                $result = $conn->query($sql_dette);
                if ($result && $result->num_rows > 0) {
                    $dette = $result->fetch_assoc();
                    $quantite_restituee = $dette['quantite'];

                    if ($montant_paye > $dette['montant_restant']) {
                        echo "<div class='alert alert-danger'>Erreur: Le montant payé dépasse le montant restant de la dette.</div>";
                    } else {
                        $nouveau_montant_restant = $dette['montant_restant'] - $montant_paye;
                        $statut_payee = $nouveau_montant_restant == 0 ? 1 : 0;

                        $sql_payer = "UPDATE dettes SET montant_restant = $nouveau_montant_restant, payee = $statut_payee WHERE id = $dette_id";
                        if ($conn->query($sql_payer) === TRUE) {
                            if ($statut_payee) {
                                $sql_update = "UPDATE produits SET quantite = quantite + $quantite_restituee WHERE id = " . $dette['produit_id'];
                                if ($conn->query($sql_update) === TRUE) {
                                    echo "<div class='alert alert-success'>Dette mise à jour avec succès.</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Erreur lors de la mise à jour du stock: " . $conn->error . "</div>";
                                }
                            } else {
                                echo "<div class='alert alert-success'>Dette mise à jour avec succès.</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Erreur lors de la mise à jour de la dette: " . $conn->error . "</div>";
                        }
                    }
                } else {
                    echo "<div class='alert alert-danger'>Erreur: Dette non trouvée.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Erreur: Tous les champs sont requis.</div>";
            }
        }
    }

    // Récupérer les noms des produits pour la liste déroulante
    $sql = "SELECT nom FROM produits";
    $result = $conn->query($sql);
    $options = "";
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row['nom'] . "'>" . $row['nom'] . "</option>";
        }
    } else {
        echo "<div class='alert alert-danger'>Aucun produit trouvé.</div>";
    }

    $conn->close();
    ?>

    <form method="post" action="" class="mb-3">
        <div class="form-group">
            <label for="produit_nom">Produit:</label>
            <select name="produit_nom" class="form-control" id="produit_nom">
                <?php echo $options; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" class="form-control" id="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="quantite">Quantité:</label>
            <input type="number" name="quantite" class="form-control" id="quantite" required>
        </div>
        <div class="form-group">
            <label for="prix_unitaire">Prix unitaire:</label>
            <input type="number" step="0.01" name="prix_unitaire" class="form-control" id="prix_unitaire" required>
        </div>
        <button type="submit" name="enregistrer" class="btn btn-primary">Enregistrer dette</button>
    </form>

    <form method="post" action="">
        <div class="form-group">
            <label for="dette_id">Dette ID:</label>
            <input type="number" name="dette_id" class="form-control" id="dette_id" required>
        </div>
        <div class="form-group">
            <label for="montant_paye">Montant à payer:</label>
            <input type="number" step="0.01" name="montant_paye" class="form-control" id="montant_paye" required>
        </div>
        <button type="submit" name="payer" class="btn btn-success">Payer dette</button>
    </form>
</div>
</body>
</html>
