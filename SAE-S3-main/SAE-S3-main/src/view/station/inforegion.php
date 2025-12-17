<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Départements et Stations</title>
</head>
<body class="anto">

    <div class="separation">
        <?php
        usort($departements, function($a, $b) {
            return $a->getCode_dept() <=> $b->getCode_dept();
        });

        $departementActuel = 'pas de département précisé';
        foreach ($departements as $departement) {  
            $departementUrl = rawurlencode($departement->getId_station()); // Pour les URLs
            $departementHtml = htmlspecialchars($departement->getId_station(), ENT_QUOTES, 'UTF-8'); // Pour le HTML
            
            if ($departementActuel !== $departement->getNom_dept()) {
                $departementActuel = $departement->getNom_dept();
                echo "<h3 id='letitrer'>\nDépartement $departementActuel :\n</h3>";
                echo '<a class="text5" href="../web/frontController.php?action=rechercheStation&controller=station">Recherche station</a>';
            }

            echo '<p class="text5">
                station : <a href="../web/frontController.php?action=readStationDash&controller=station&station_id=' . $departementUrl . '">' . $departementHtml . '</a>
                </p>';
        }
        ?>
    </div>

</body>
</html>
