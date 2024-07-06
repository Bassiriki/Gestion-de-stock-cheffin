<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaire et Dettes</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
          body {
    background-color: #dcdcdc;
    color: #000000;
}

.table-custom {
    width: 100%;
    margin-bottom: 1rem;
    border-collapse: collapse;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.3s ease-in-out;
}

.table-custom:hover {
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.25);
}

.table-custom th,
.table-custom td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
    transition: background-color 0.3s ease-in-out, color 0.3s ease-in-out;
}

.table-custom thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    background-color: #343a40;
    color: #ffffff;
    font-weight: bold;
    text-transform: uppercase;
}

.table-custom tbody tr:hover {
    background-color: #f5f5f5;
}

.table-custom tbody + tbody {
    border-top: 2px solid #dee2e6;
}

.table-custom .col-green-light {
    background-color: lightgreen;
}

.table-custom .col-orange-light {
    background-color: #ffc107;
}

.table-custom .col-red-light {
    background-color: lightcoral;
}

.nav-link {
    display: flex;
    align-items: center;
}

.nav-link i {
    margin-right: 8px;
    font-size: 1.2rem;
    transition: transform 0.3s ease-in-out;
}

.nav-link:hover i {
    transform: translateX(5px);
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
                    <a class="nav-link" href="index.php"><i class="fas fa-home"></i>Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="inventaire.php"><i class="fas fa-warehouse"></i>Inventaire</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="operation.php"><i class="fas fa-cogs"></i>Opérations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dettes.php"><i class="fas fa-hand-holding-usd"></i>Dettes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ajouter_produit.php"><i class="fas fa-plus-circle"></i>Ajouter un produit</a>
                </li>
            </ul>
        </div>
        
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mt-5">
        <h2>Inventaire</h2>
        <?php
        include 'db.php';
        

        // Affichage de l'inventaire
        $sql = "SELECT p.id, p.nom, p.quantite, p.prix,
                       (SELECT IFNULL(SUM(quantite), 0) FROM operations WHERE produit_id = p.id AND type_operation='entree') as total_entrees,
                       (SELECT IFNULL(SUM(prix_total), 0) FROM operations WHERE produit_id = p.id AND type_operation='entree') as montant_total_entrees,
                       (SELECT IFNULL(SUM(quantite), 0) FROM operations WHERE produit_id = p.id AND type_operation='sortie') as total_sorties,
                       (SELECT IFNULL(SUM(prix_total), 0) FROM operations WHERE produit_id = p.id AND type_operation='sortie') as montant_total_sorties,
                       (SELECT IFNULL(SUM(quantite), 0) FROM dettes WHERE produit_id = p.id AND payee = 0) as total_dettes,
                       (SELECT IFNULL(SUM(prix_total), 0) FROM dettes WHERE produit_id = p.id AND payee = 1) as montant_total_dettes_payees
                       
                FROM produits p";
        $result = $conn->query($sql);
        

        $labels = [];
        $entrees = [];
        $sorties  = [];

        if (!$result) {
            // Erreur de requête SQL
            echo "<div class='alert alert-danger'>Erreur SQL: " . $conn->error . "</div>";
        } else {
            if ($result->num_rows > 0) {
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-custom'>";
                echo "<thead class='thead-dark'><tr><th>Nom</th><th>Quantité Disponible</th><th>Prix</th><th>Total Entrées</th><th>Montant Total Entrées</th><th>Total Sorties</th><th>Montant Total Sorties</th><th>Total Dettes</th><th>Montant Total Dettes Payées</th> </tr></thead>";
                echo "<tbody>";
                while($row = $result->fetch_assoc()) {
                    $nom = $row["nom"];
                    $quantite = $row["quantite"];
                    $prix = $row["prix"];
                    $total_entrees = $row["total_entrees"];
                    $montant_total_entrees = $row["montant_total_entrees"];
                    $total_sorties = $row["total_sorties"];
                    $montant_total_sorties = $row["montant_total_sorties"];
                    $total_dettes = $row["total_dettes"];
                    $montant_total_dettes_payees = $row["montant_total_dettes_payees"];

                    // Calcul de la somme totale
                     
                    
                    // Détermination de la classe CSS pour la couleur de fond
                    $montant_total_sorties = $montant_total_dettes_payees+$montant_total_sorties;
                    $total_sorties = $total_dettes + $total_sorties;
                    
                    echo "<tr>";
                    echo "<td>$nom</td>";
                    echo "<td>$quantite</td>";
                    echo "<td>$prix</td>";
                    echo "<td>$total_entrees</td>";
                    echo "<td class='col-green-light'>$montant_total_entrees</td>";
                    echo "<td>$total_sorties</td>";
                    echo "<td class='col-orange-light'>$montant_total_sorties</td>";
                    echo "<td>$total_dettes</td>";
                    echo "<td class='col-green-light'>$montant_total_dettes_payees</td>";
                
                    echo "</tr>";

                    // Ajouter les données au graphique
                    $labels[] = $nom;
                    $entrees[] = $total_entrees;
                    $sorties[] = $total_sorties;
                }
                echo "</tbody></table>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning'>Aucun produit trouvé dans l'inventaire.</div>";
            }
        }

        // Affichage des dettes
        $sql_dettes = "SELECT d.id, p.nom, d.description, d.quantite, d.prix_unitaire, d.prix_total, d.montant_restant, d.payee, d.date_dette
                       FROM dettes d
                       JOIN produits p ON d.produit_id = p.id
                       ORDER BY d.date_dette DESC";
        $result_dettes = $conn->query($sql_dettes);

        if (!$result_dettes) {
            // Erreur de requête SQL pour les dettes
            echo "<div class='alert alert-danger'>Erreur SQL: " . $conn->error . "</div>";
        } else {
            if ($result_dettes->num_rows > 0) {
                echo "<h2>Dettes</h2>";
                echo "<div class='table-responsive'>";
                echo "<table class='table table-bordered table-custom'>";
                echo "<thead class='thead-dark'><tr><th>ID</th><th>Produit</th><th>Description</th><th>Quantité</th><th>Prix Unitaire</th><th>Prix Total</th><th>Montant Restant</th><th>Date</th><th>Statut</th></tr></thead>";
                echo "<tbody>";
                while($row = $result_dettes->fetch_assoc()) {
                    $statut = $row['payee'] ? "Payée" : "Non payée";
                    $statut_class = $row['payee'] ? 'col-green-light' : 'col-red-light';

                    echo "<tr>";
                    echo "<td>" . $row["id"]. "</td>";
                    echo "<td>" . $row["nom"]. "</td>";
                    echo "<td>" . $row["description"]. "</td>";
                    echo "<td>" . $row["quantite"]. "</td>";
                    echo "<td>" . $row["prix_unitaire"]. "</td>";
                    echo "<td>" . $row["prix_total"]. "</td>";
                    echo "<td>" . $row["montant_restant"] . "</td>";
                    echo "<td>" . $row["date_dette"] . "</td>";
                    echo "<td class='$statut_class'>" . $statut . "</td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-warning'>Aucune dette trouvée.</div>";
            }
        }

        $conn->close();
        ?>
    </div>

    <!-- Diagramme en courbes -->
    <div class="container mt-5">
        <h2>Analyse des Entrées et Sorties</h2>
        <canvas id="chart"></canvas>
    </div>

    <!-- Scripts JavaScript requis par Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <!-- Script pour le diagramme -->
    <script>
        var ctx = document.getElementById('chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [
                    {
                        label: 'Entrées',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        data: <?php echo json_encode($entrees); ?>
                    },
                    {
                        label: 'Sorties',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        data: <?php echo json_encode($sorties); ?>
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    xAxes: [{
                        display: true
                    }],
                    yAxes: [{
                        display: true
                    }]
                }
            }
        });
    </script>
</body>
</html>
