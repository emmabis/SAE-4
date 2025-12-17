<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations sur la Station</title>
    <link rel="stylesheet" href="../web/assets/css/list.css">
</head>
<body>
<div class="containerr">
    <div class="info-container">
    <?php
    $fields = [
        "Nom de la Station" => htmlspecialchars($station->getNom()), 
        "ID Station" => htmlspecialchars($station->getId_station()),    
        "Latitude" => htmlspecialchars($station->getLatitude()),
        "Longitude" => htmlspecialchars($station->getLongitude()),
        "Altitude" => htmlspecialchars($station->getAltitude()) . " mètres",
        "Code Géographique" => htmlspecialchars($station->getCode_geo()),
        "Libellé Géographique" => htmlspecialchars($station->getLib_geo()),
        "Code Postale" => htmlspecialchars($station->getCode_dept()),
        "Nom Département" => htmlspecialchars($station->getNom_dept()),
        "Numero de la Région" => htmlspecialchars($station->getCode_reg()),
        "Nom de la Région" => htmlspecialchars($station->getNom_reg()),
        "Code EPCI" => htmlspecialchars($station->getCode_epci()),
        "Nom EPCI" => htmlspecialchars($station->getNom_epci())
    ];

    foreach ($fields as $title => $value) {
        echo "<div class='info-block'>";
        echo "<h2 class='info-title'>$title</h2>";
        echo "<p class='info-value'>$value</p>";
        echo "</div>";
    }
?>

    </div>
</div>
</body>
</html>
