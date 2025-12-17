<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de lien API</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Générateur de lien API</h1>
    <form method="GET" action="">
        <label for="numerStation">Numéro de Station :</label>
        <input type="text" id="station" name="numerStation" list="stations" required>
        <datalist id="stations">
            <option value="07110">
            <option value="07515">
            <option value="12345">
        </datalist><br><br>

        <label for="startDate">Date de début :</label>
        <input type="date" name="startDate" id="startDate"><br>
        <label for="endDate">Date de fin :</label>
        <input type="date" name="endDate" id="endDate"><br><br>

        <h3>Filtres :</h3>
        <label for="tempMin">Température min :</label>
        <input type="number" name="tempMin" id="tempMin"><br>
        <label for="tempMax">Température max :</label>
        <input type="number" name="tempMax" id="tempMax"><br><br>

        <h3>Champs à inclure :</h3>
        <label><input type="checkbox" name="n"> N</label><br>
        <label><input type="checkbox" name="cm"> CM</label><br>
        <label><input type="checkbox" name="ch"> CH</label><br>
        <label><input type="checkbox" name="hbas"> HBAS</label><br>
        <label><input type="checkbox" name="t"> T</label><br>
        <label><input type="checkbox" name="u"> U</label><br>
        <label><input type="checkbox" name="vv"> VV</label><br>
        <label><input type="checkbox" name="dd"> DD</label><br>
        <label><input type="checkbox" name="ff"> FF</label><br>
        <label><input type="checkbox" name="pres"> PRES</label><br>
        <label><input type="checkbox" name="niv_bar"> Niv_Bar</label><br>
        <label><input type="checkbox" name="tend"> TEND</label><br>
        <label><input type="checkbox" name="cod_tend"> COD_TEND</label><br><br>

        <button type="submit">Générer le lien</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['numerStation'])) {
        function construireLienAPI($numerStation, $params, $startDateTime, $endDateTime) {
            $baseURL = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records";

            $selectedValues = ["numer_sta"];
            foreach ($params as $key => $value) {
                if ($value) $selectedValues[] = $key;
            }

            $selectFields = implode(",", $selectedValues);

            $queryParams = [
                "select" => $selectFields,
                "where" => "numer_sta=$numerStation AND date >= '$startDateTime' AND date < '$endDateTime'",
                "order_by" => "date DESC",
                "limit" => 20,
                "lang" => "fr",
                "timezone" => "Europe/Berlin"
            ];

            if (!empty($_GET['tempMin']) && !empty($_GET['tempMax'])) {
                $queryParams['where'] .= " AND t >= " . (float)$_GET['tempMin'];
                $queryParams['where'] .= " AND t <= " . (float)$_GET['tempMax'];
            }

            return "$baseURL?" . http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);
        }

        $numerStation = $_GET['numerStation'];
        $params = [
            "date" => isset($_GET['date']),
            "n" => isset($_GET['n']),
            "cm" => isset($_GET['cm']),
            "ch" => isset($_GET['ch']),
            "hbas" => isset($_GET['hbas']),
            "t" => isset($_GET['t']),
            "u" => isset($_GET['u']),
            "vv" => isset($_GET['vv']),
            "dd" => isset($_GET['dd']),
            "ff" => isset($_GET['ff']),
            "pres" => isset($_GET['pres']),
            "niv_bar" => isset($_GET['niv_bar']),
            "tend" => isset($_GET['tend']),
            "cod_tend" => isset($_GET['cod_tend'])
        ];

        $startDateTime = isset($_GET['startDate']) ? $_GET['startDate'] . "T00:00:00" : date("Y-m-d", strtotime("-1 day")) . "T00:00:00";
        $endDateTime = isset($_GET['endDate']) ? $_GET['endDate'] . "T23:59:59" : date("Y-m-d") . "T23:59:59";

        $lien = construireLienAPI($numerStation, $params, $startDateTime, $endDateTime);
        echo "<h3>Lien généré :</h3><a href='$lien' target='_blank'>$lien</a>";

        $jsonData = @file_get_contents($lien);
        if ($jsonData === false) {
            $error = error_get_last();
            echo "<p>Erreur lors de la requête à l'API : {$error['message']}</p>";
            exit;
        }

        $data = json_decode($jsonData, true);
        if (isset($data['results']) && is_array($data['results']) && count($data['results']) > 0) {
            echo "<h3>Résultats :</h3>";
            $meteo = $data['results'][0]; // Prendre le premier résultat pour exemple

            $infosMeteo = [
                'Température' => $meteo['t'] . "°C",
                'Humidité' => $meteo['u'] . "%",
                'Pression' => $meteo['pres'] . " hPa",
                'Vitesse du vent' => $meteo['ff'] . " km/h",
                'Direction du vent' => $meteo['dd'] . "°",
                'Type de nuage' => $meteo['n'] ?? 'Non spécifié',
                'Nébulosité totale' => $meteo['niv_bar'] ?? 'Non spécifié',
                'Visibilité horizontale' => $meteo['vv'] . "m",
                'Hauteur de base des nuages' => $meteo['hbas'] . "m",
                'Variation pression en 3h' => $meteo['tend'] ?? 'Non spécifié',
                'Type de la tendance barométrique' => $meteo['cod_tend'] ?? 'Non spécifié',
                'Temps présent' => $meteo['cm'] ?? 'Non spécifié'
            ];

            foreach ($infosMeteo as $label => $valeur) {
                echo "<p><strong>{$label}:</strong> {$valeur}</p>";
            }

            echo "<h3>Graphique des températures :</h3>";
            echo "<canvas id='chart' width='400' height='200'></canvas>";
            $labels = json_encode(array_column($data['results'], 'date'));
            $values = json_encode(array_column($data['results'], 't'));
            echo "<script>
                const ctx = document.getElementById('chart').getContext('2d');
                const chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: $labels,
                        datasets: [{
                            label: 'Température',
                            data: $values,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    }
                });
            </script>";
        } else {
            echo "<p>Aucun résultat trouvé ou réponse invalide.</p>";
        }
    }
    ?>
</body>
</html>
