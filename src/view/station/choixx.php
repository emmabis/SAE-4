<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .location-inputs {
            display: none;
        }
    </style>
    <script>
        function updateLocationInputs() {
            // Masquer tous les champs de localisation
            document.querySelectorAll('.location-inputs').forEach(el => el.style.display = 'none');

            // Récupérer la sélection
            const locationType = document.getElementById('location-type').value;

            // Afficher la section correspondante
            if (locationType) {
                document.getElementById(`${locationType}-inputs`).style.display = 'block';
            }

            // Mettre à jour la valeur du type de localisation dans l'input caché
            document.getElementById('type_localisation').value = locationType;
        }
    </script>
</head>
<body class="bodyrecherche">
    <h1 class="meteo-form-title">Choix d'un lieu</h1>
    <form class="meteo-form" action="../web/frontController.php" method="get">
        <?php
        // Determine current selection
        $selectedType = isset($_GET['type']) ? $_GET['type'] : '';
        $selectedValue = isset($_GET['valeur']) ? $_GET['valeur'] : '';

        // Generate query string
        $queryParams = [];
        if ($selectedType) {
            $queryParams['type'] = $selectedType;
            $queryParams['valeur'] = $selectedValue;
            $queryParams['valeur-lieu'] = $selectedValue;
        }
        $queryString = http_build_query($queryParams);
        ?>

        <input type="hidden" id="query" name="query" value="<?php echo htmlspecialchars($queryString); ?>">
        <input type="hidden" name="de" value="<?php echo htmlspecialchars($queryString); ?>">
        <input type="hidden" name="action" value="test">
        <input type="hidden" name="type2" value='<?php echo $_GET["mois"]; ?>'>
        <input type="hidden" name="controller" value="station">

        <!-- Location Type Selection -->
        <div class="meteo-form-group">
            <label for="location-type" class="meteo-label">Type de localisation :</label>
            <select id="location-type" name="type" class="meteo-select" onchange="updateLocationInputs()">
                <option value="">Choisir une localisation</option>
                <option value="station" <?php echo ($selectedType == 'station') ? 'selected' : ''; ?>>Station</option>
                <option value="departement" <?php echo ($selectedType == 'departement') ? 'selected' : ''; ?>>Département</option>
                <option value="region" <?php echo ($selectedType == 'region') ? 'selected' : ''; ?>>Région</option>
                <option value="national" <?php echo ($selectedType == 'national') ? 'selected' : ''; ?>>National</option>
            </select>
        </div>

        <!-- Station Selection -->
        <div id="station-inputs" class="location-inputs meteo-form-group" 
             style="display: <?php echo ($selectedType == 'station') ? 'block' : 'none'; ?>">
            <label for="station" class="meteo-label">Sélectionner une station :</label>
            <select id="station" name="valeur1" class="meteo-select">
                <?php
                $regionsAffichees = [];
                foreach ($stations as $station) {
                    $stationId = $station->getId_station();
                    if (!in_array($stationId, $regionsAffichees) && $stationId != "il y a rien") {
                        $selected = ($selectedValue == $stationId) ? 'selected' : '';
                        echo "<option value='$stationId' $selected>$stationId</option>";
                        $regionsAffichees[] = $stationId;
                    }
                }
                ?>
            </select>
        </div>

        <!-- Department Selection -->
        <div id="departement-inputs" class="location-inputs meteo-form-group" 
             style="display: <?php echo ($selectedType == 'departement') ? 'block' : 'none'; ?>">
            <label for="departement" class="meteo-label">Sélectionner un département :</label>
            <select id="departement" name="valeur2" class="meteo-select">
                <?php
                $regionsAffichees = [];
                foreach ($stations as $station) {
                    $departement = $station->getNom_dept();
                    if (!in_array($departement, $regionsAffichees) && $departement != "il y a rien") {
                        $selected = ($selectedValue == $departement) ? 'selected' : '';
                        echo "<option value='$departement' $selected>$departement</option>";
                        $regionsAffichees[] = $departement;
                    }
                }
                ?>
            </select>
        </div>

        <!-- Region Selection -->
        <div id="region-inputs" class="location-inputs meteo-form-group" 
             style="display: <?php echo ($selectedType == 'region') ? 'block' : 'none'; ?>">
            <label for="region" class="meteo-label">Sélectionner une région :</label>
            <select id="region" name="valeur3" class="meteo-select">
                <?php
                $regionsAffichees = [];
                foreach ($stations as $station) {
                    $region = $station->getNom_reg();
                    if (!in_array($region, $regionsAffichees) && $region != "il y a rien") {
                        $selected = ($selectedValue == $region) ? 'selected' : '';
                        echo "<option value='$region' $selected>$region</option>";
                        $regionsAffichees[] = $region;
                    }
                }
                ?>
            </select>
        </div>

        <!-- National Option -->
        <div id="national-inputs" class="location-inputs meteo-form-group" 
             style="display: <?php echo ($selectedType == 'national') ? 'block' : 'none'; ?>">
            <label for="national" class="meteo-label">Météo Nationale :</label>
            <input type="hidden" id="national" value="France" readonly>
        </div>

        <button type="submit" class="meteo-button">Choisir</button>

        <!-- Additional Hidden Inputs -->
        <input type="hidden" id="type_localisation" name="type_localisation" value="<?php echo $selectedType; ?>">
        <input type="hidden" name="mois-choisi" value="<?php echo $_GET['mois']; ?>">
        <input type="hidden" name="semaine-choisi" value="<?php echo $_GET['semaine']; ?>">
        <input type="hidden" name="saison-choisi" value="<?php echo $_GET['saison']; ?>">
        <input type="hidden" name="jour-choisi" value="<?php echo $_GET['jour']; ?>">
        <input type="hidden" name="annee-type" value="<?php echo isset($_GET['annee']) ? $_GET['annee'] : ''; ?>">
        
    </form>
</body>
</html>