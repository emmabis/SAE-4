<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Météothèque</title>

  <!-- style a deplacer jsp ou css -->
  <style>
        .date-inputs {
            display: none;
        }
        .date-inputs.active {
            display: block;
        }
        .form-group {
            margin-bottom: 15px;
        }
        select, input {
            padding: 5px;
            margin: 5px 0;
        }
    </style>

    <script>
        function RegOuDept() {
            const regionSelect = document.getElementById('region');
            const departementSelect = document.getElementById('departement');
            const hiddenField = document.getElementById('type_localisation');

            if (regionSelect.value) {
                departementSelect.disabled = true;
                hiddenField.value = 'region';
            } else {
                departementSelect.disabled = false;
            }

            if (departementSelect.value) {
                regionSelect.disabled = true;
                hiddenField.value = 'departement';
            } else {
                regionSelect.disabled = false;
            }

            if (!regionSelect.value && !departementSelect.value) {
                hiddenField.value = '';
            }
        }

        function updateDateInputs() {
            const intervalType = document.getElementById('interval-type').value;
            const dateDebut = document.getElementById('date_debut');
            const dateFin = document.getElementById('date_fin');

            dateDebut.style.display = "block";
            dateFin.style.display = "block";

            dateDebut.removeAttribute('min');
            dateDebut.removeAttribute('max');
            dateFin.removeAttribute('min');
            dateFin.removeAttribute('max');

            document.querySelectorAll('.date-inputs').forEach(el => {
                if (el.id !== 'custom-inputs') {
                    el.classList.remove('active');
                }
            });

            if (intervalType === 'semaine') {
                document.getElementById('custom-inputs').classList.remove('active');
            } else {
                document.getElementById('custom-inputs').classList.add('active');
            }

            if (intervalType) {
                const activeSection = document.getElementById(`${intervalType}-inputs`);
                if (activeSection) {
                    activeSection.classList.add('active'); // Ajouter `active` à la section correspondante
                }
            }

            dateDebut.removeAttribute('min');
            dateDebut.removeAttribute('max');
            dateFin.removeAttribute('min');
            dateFin.removeAttribute('max');

            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();

            // Appliquer des restrictions en fonction du type d'intervalle
            switch (intervalType) {
                case 'semaine':
                    dateDebut.type = 'date';
                    dateFin.type = 'date';
                    dateDebut.addEventListener('change', () => limiteSemaine(dateDebut, dateFin));
                    dateFin.addEventListener('change', () => limiteSemaine(dateDebut, dateFin));
                    break;

                case 'mois':
                    updateFiltreMois();
                    break;

                default:
                    dateDebut.type = 'date';
                    dateFin.type = 'date';
                    break;
            }
        }

    function updateFiltreMois() {
        const moisSelectionne = document.getElementById('mois').value;
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');

        if (moisSelectionne) {
            const [annee, mois] = moisSelectionne.split('-');
            const premierJour = `${annee}-${mois}-01`;
            const dernierJour = new Date(annee, mois, 0).toISOString().split('T')[0]; // Dernier jour du mois

            // Limiter les dates disponibles dans le mois sélectionné
            dateDebut.min = premierJour;
            dateDebut.max = dernierJour;
            dateFin.min = premierJour;
            dateFin.max = dernierJour;

            console.log(`Sélection de dates limitée à : ${premierJour} - ${dernierJour}`);
        } else {
            // Réinitialiser si aucun mois n'est sélectionné
            dateDebut.removeAttribute('min');
            dateDebut.removeAttribute('max');
            dateFin.removeAttribute('min');
            dateFin.removeAttribute('max');
        }
    }

    function updateFiltreSaison() {
        const saisonSelectionnee = document.getElementById('saison').value;
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');

        if (saisonSelectionnee) {
            const [annee, saison] = saisonSelectionnee.split('-');
            let minDate, maxDate;

            // Définir les plages de dates pour chaque saison
            switch (saison) {
                case 'printemps':
                    minDate = `${annee}-03-21`; // 21 mars
                    maxDate = `${annee}-06-20`; // 20 juin
                    break;
                case 'ete':
                    minDate = `${annee}-06-21`; // 21 juin
                    maxDate = `${annee}-09-22`; // 22 septembre
                    break;
                case 'automne':
                    minDate = `${annee}-09-23`; // 23 septembre
                    maxDate = `${annee}-12-20`; // 20 décembre
                    break;
                case 'hiver':
                    minDate = `${annee}-12-21`; // 21 décembre
                    maxDate = `${parseInt(annee) + 1}-03-20`; // 20 mars de l'année suivante
                    break;
                default:
                    minDate = "";
                    maxDate = "";
            }

            // Appliquer les limites
            dateDebut.min = minDate;
            dateDebut.max = maxDate;
            dateFin.min = minDate;
            dateFin.max = maxDate;

            // Réinitialiser les valeurs si elles ne sont pas valides
            if (dateDebut.value < minDate || dateDebut.value > maxDate) {
                dateDebut.value = "";
            }
            if (dateFin.value < minDate || dateFin.value > maxDate) {
                dateFin.value = "";
            }

            console.log(`Sélection de dates limitée à la saison : ${minDate} - ${maxDate}`);
        } else {
            // Réinitialiser si aucune saison n'est sélectionnée
            dateDebut.removeAttribute('min');
            dateDebut.removeAttribute('max');
            dateFin.removeAttribute('min');
            dateFin.removeAttribute('max');
        }
    }

    function limiteSemaine(dateDebut, dateFin) {
        if (dateDebut.value) {
            const debut = new Date(dateDebut.value);
            const fin = new Date(debut);
            fin.setDate(debut.getDate() + 6); // Limite à 7 jours

            dateFin.min = debut.toISOString().split('T')[0];
            dateFin.max = fin.toISOString().split('T')[0];
        }
    }

    function limiteMois(dateDebut, dateFin) {
        if (dateDebut.value) {
            const debut = new Date(dateDebut.value);
            const mois = debut.getMonth();
            const annee = debut.getFullYear();

            const dernierJour = new Date(annee, mois + 1, 0); // Dernier jour du mois
            dateFin.min = debut.toISOString().split('T')[0];
            dateFin.max = dernierJour.toISOString().split('T')[0];
        }
    }

    function calculerDates() {
        const intervalType = document.getElementById('interval-type').value;
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');

        if (intervalType === 'semaine') {
            limiteSemaine(dateDebut, dateFin);
        } else if (intervalType === 'mois') {
            limiteMois(dateDebut, dateFin);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');
        const moisSelect = document.getElementById('mois');
        const saisonSelect = document.getElementById('saison');

        saisonSelect.addEventListener('change', updateFiltreSaison);
        moisSelect.addEventListener('change', updateFiltreMois);
        dateDebut.addEventListener('change', validateDateRange);
        dateFin.addEventListener('change', validateDateRange);
        updateDateInputs();
    });
    </script>
</head>
<body id="createmeteo">
    <h1 class="meteo-form-title">Formulaire Météothèque</h1>
    <form class="meteo-form" action="../web/frontController.php?action=created&controller=meteotheque" method="post">
        <div class="meteo-form-group">
            <label for="titre" class="meteo-label">Titre :</label>
            <input type="text" id="titre" name="titre" maxlength="64" class="meteo-input" required>
        </div>

        <div class="meteo-form-group">
            <label for="region" class="meteo-label">Région :</label>
            <select id="region" name="localisation" class="meteo-select" onchange="RegOuDept()">
                <option value="">-- Sélectionnez une région --</option>
                <?php
                    $regionsAffichees = [];
                    foreach ($stations as $station) {
                        $region = $station->getNom_reg();
                        if (!in_array($region, $regionsAffichees) && $region != "il y a rien") {
                            echo "<option value='$region'>$region</option>";
                            $regionsAffichees[] = $region;
                        }
                    }
                ?>
            </select>
        </div>

        <div class="meteo-form-group">
            <label for="departement" class="meteo-label">Département :</label>
            <select id="departement" name="localisation" class="meteo-select" onchange="RegOuDept()">
                <option value="">-- Sélectionnez un département --</option>
                <?php
                    $departementsAffichees = [];
                    foreach ($stations as $station) {
                        $departement = $station->getNom_dept();
                        if (!in_array($departement, $departementsAffichees) && $departement != "il y a rien") {
                            echo "<option value='$departement'>$departement</option>";
                            $departementsAffichees[] = $departement;
                        }
                    }
                ?>
            </select>
        </div>

        <input type="hidden" id="type_localisation" name="type_localisation" value="">

        <div class="meteo-form-group">
            <label for="interval-type" class="meteo-label">Type d'intervalle :</label>
            <select id="interval-type" class="meteo-select" onchange="updateDateInputs()">
                <option value="">Personnalisé</option>
                <option value="semaine">Semaine</option>
                <option value="mois">Mois</option>
                <option value="saison">Saison</option>
                <option value="annee">Année</option>
            </select>
        </div>

        <!-- Sélection de semaine -->
        <div id="semaine-inputs" class="date-inputs meteo-form-group">
            <label for="semaine" class="meteo-label">Sélectionner une semaine :</label>
            <input type="week" id="semaine" class="meteo-input" onchange="calculerDates()">
        </div>

        <!-- Sélection de mois -->
        <div id="mois-inputs" class="date-inputs meteo-form-group">
            <label for="mois" class="meteo-label">Sélectionner un mois :</label>
            <input type="month" id="mois" class="meteo-input" onchange="updateFiltreMois()">
        </div>

        <!-- Sélection de saison -->
        <div id="saison-inputs" class="date-inputs meteo-form-group">
            <label for="saison" class="meteo-label">Sélectionner une saison :</label>
            <select id="saison" class="meteo-select" onchange="calculerDates()">
                <?php
                $anneeActuelle = date('Y');
                for ($annee = $anneeActuelle - 5; $annee <= $anneeActuelle + 5; $annee++) {
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

        <!-- Sélection d'année -->
        <div id="annee-inputs" class="date-inputs meteo-form-group">
            <label for="annee" class="meteo-label">Sélectionner une année :</label>
            <select id="annee" class="meteo-select" onchange="calculerDates()">
                <?php
                for ($annee = $anneeActuelle - 5; $annee <= $anneeActuelle + 5; $annee++) {
                    echo "<option value='$annee'>$annee</option>";
                }
                ?>
            </select>
        </div>

        <!-- Dates personnalisées -->
        <div class="date-inputs meteo-form-group active" id="custom-inputs">
            <label for="date_debut" class="meteo-label">Date de début :</label>
            <input type="date" id="date_debut" class="meteo-input" name="date_debut" required>

            <label for="date_fin" class="meteo-label">Date de fin :</label>
            <input type="date" id="date_fin" class="meteo-input" name="date_fin" required>
        </div>

        <div class="meteo-form-group">
            <label for="prive" class="meteo-label">Privé :</label>
            <input type="checkbox" id="prive" class="meteo-checkbox" name="prive" value="1">
        </div>

        <button type="submit" class="meteo-button">Créer la météothèque</button>
    </form>
</body>
</html>
