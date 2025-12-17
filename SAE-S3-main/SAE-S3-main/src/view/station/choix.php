<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Météothèque</title>
    <style>
        .date-inputs {
            display: none;
        }
        .meteo-form-group {
            margin: 15px 0;
        }
        .meteo-select, .meteo-input {
            padding: 8px;
            margin: 5px 0;
        }
    </style>
    <script>
        function updateDateInputs() {
            // Masquer tous les champs de date
            document.querySelectorAll('.date-inputs').forEach(el => el.style.display = 'none');

            // Récupérer la sélection
            const intervalType = document.getElementById('interval-type').value;

            // Afficher la section correspondante
            if (intervalType) {
                document.getElementById(`${intervalType}-inputs`).style.display = 'block';
            }

            // Mettre à jour la valeur du type d'intervalle dans l'input caché
            document.getElementById('type_localisation').value = intervalType;
        }

        function generateWeekOptions() {
            const weekSelect = document.getElementById('semaine');
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();

            // Générer les options pour les 5 dernières années jusqu'aux 5 prochaines années
            for (let year = currentYear - 5; year <= currentYear + 5; year++) {
                const optgroup = document.createElement('optgroup');
                optgroup.label = year;

                // Générer 52 semaines pour chaque année
                for (let week = 1; week <= 52; week++) {
                    const option = document.createElement('option');
                    const weekNum = week.toString().padStart(2, '0');
                    option.value = `${year}-W${weekNum}`;
                    option.textContent = `Semaine ${weekNum} de ${year}`;
                    optgroup.appendChild(option);
                }

                weekSelect.appendChild(optgroup);
            }
        }

        function generateMonthOptions() {
            const monthSelect = document.getElementById('mois');
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const months = [
                'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ];

            // Générer les options pour les 5 dernières années jusqu'aux 5 prochaines années
            for (let year = currentYear - 5; year <= currentYear + 5; year++) {
                const optgroup = document.createElement('optgroup');
                optgroup.label = year;

                months.forEach((month, index) => {
                    const option = document.createElement('option');
                    const monthNum = (index + 1).toString().padStart(2, '0');
                    option.value = `${year}-${monthNum}`;
                    option.textContent = `${month} ${year}`;
                    optgroup.appendChild(option);
                });

                monthSelect.appendChild(optgroup);
            }
        }

        // Initialiser les sélecteurs au chargement de la page
        window.onload = function() {
            generateWeekOptions();
            generateMonthOptions();
        };
    </script>
</head>
<body class="bodyrecherche">
    <h1 class="meteo-form-title">Choix d'un intervalle temporel</h1>
    <form class="meteo-form" action="../web/frontController.php" method="get">
        <input type="hidden" id="type_localisation" name="type_localisation" value="">
        <input type="hidden" id="controller" name="controller" value="station">
        <input type="hidden" id="action" name="action" value="choixx">

        <div class="meteo-form-group">
            <label for="interval-type" class="meteo-label">Type d'intervalle :</label>
            <select id="interval-type" class="meteo-select" name="type" onchange="updateDateInputs()">
                <option value="">Personnalisé</option>
                <option value="jour">Jour</option>
                <option value="semaine">Semaine</option>
                <option value="mois">Mois</option>
                <option value="saison">Saison</option>
                <option value="annee">Année</option>
            </select>
        </div>

        <div id="jour-inputs" class="date-inputs meteo-form-group">
            <label for="jour" class="meteo-label">Sélectionner un jour :</label>
            <input type="date" id="jour" name="jour" class="meteo-input">
        </div>

        <div id="semaine-inputs" class="date-inputs meteo-form-group">
            <label for="semaine" class="meteo-label">Sélectionner une semaine :</label>
            <select id="semaine" name="semaine" class="meteo-select"></select>
        </div>

        <div id="mois-inputs" class="date-inputs meteo-form-group">
            <label for="mois" class="meteo-label">Sélectionner un mois :</label>
            <select id="mois" name="mois" class="meteo-select"></select>
        </div>

        <div id="saison-inputs" class="date-inputs meteo-form-group">
            <label for="saison" class="meteo-label">Sélectionner une saison :</label>
            <select id="saison" name="saison" class="meteo-select">
                <?php
                $anneeActuelle = date('Y');
                for ($annee = $anneeActuelle - 10; $annee <= $anneeActuelle; $annee++) {
                    echo "<optgroup label='$annee'>";
                    echo "<option value='$annee-printemps'>Printemps $annee</option>";
                    echo "<option value='$annee-ete'>Été $annee</option>";
                    echo "<option value='$annee-automne'>Automne $annee</option>";
                    echo "<option value='$annee-hiver'>Hiver $annee</option>";
                    echo "</optgroup>";
                }
                ?>
            </select>
        </div>

        <div id="annee-inputs" class="date-inputs meteo-form-group">
            <label for="annee" class="meteo-label">Sélectionner une année :</label>
            <select id="annee" name="annee" class="meteo-select">
                <?php
                for ($annee = $anneeActuelle - 10; $annee <= $anneeActuelle; $annee++) {
                    echo "<option value='$annee'>$annee</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" class="meteo-button">Choisir</button>
    </form>
</body>
</html>