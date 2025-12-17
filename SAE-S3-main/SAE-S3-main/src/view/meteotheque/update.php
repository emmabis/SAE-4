<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Météothèque</title>
    <style>
        h1 {
            text-align: center;
            color: #ffcc00;
            margin-bottom: 20px;
        }

        .toutsss{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

        }

        /* Style du formulaire */
        form {
            background-color: #222;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="date"],
        input[type="week"],
        input[type="month"],
        select {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="checkbox"] {
            margin-top: 10px;
        }

        select:focus, input:focus {
            border-color: #b37400;
            outline: none;
        }

        button {
            background-color: #b37400;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color:rgb(150, 98, 3);
        }

        .date-inputs {
            display: none;
        }

        .date-inputs.active {
            display: block;
        }

        .form-group small {
            color: #888;
            font-size: 14px;
        }

    input,
    select,
    button {
        font-size: 1em;
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

        const regionSelect = document.getElementById('region');
            const departementSelect = document.getElementById('departement');
            const hiddenField = document.getElementById('type_localisation');

            if (regionSelect.value) {
                departementSelect.disabled = true;
                hiddenField.value = 'region';
            } else if (departementSelect.value) {
                regionSelect.disabled = true;
                hiddenField.value = 'departement';
            }
    });
    </script>
</head>
<body class="body-updatemeteotheque">
    <div class="toutsss">
    <h1>Modification de la Météothèque</h1>
    <form action="../web/frontController.php?action=updated&controller=meteotheque" method="post">
        <div class="form-group">
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" maxlength="64" <?php echo 'value="'.htmlspecialchars($meteotheque->getTitre()).'"' ?> required>
        </div>

        <div class="form-group">
            <label for="region">Région :</label>
            <select id="region" name="localisation" onchange="RegOuDept()">
                <option value="">-- Sélectionnez une région --</option>
                <?php
                    $regionsAffichees = [];
                    if ($meteotheque->getTypeLocalisation() == "region"){
                        $localisationActuelle = $meteotheque->getlocalisation();
                    } else {
                        $localisationActuelle = null;
                    }
                    foreach ($stations as $station) {
                        $region = htmlspecialchars($station->getNom_reg());
                        if (!in_array($region, $regionsAffichees) && $region != "il y a rien") {
                            if($localisationActuelle){
                                $selected = ($region === $localisationActuelle) ? "selected" : "";
                                echo "<option value='$region' $selected>$region</option>";
                            } else {
                                echo "<option value='$region'>$region</option>";
                            }
                            $regionsAffichees[] = $region;
                        }
                    }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="departement">Département :</label>
            <select id="departement" name="localisation" onchange="RegOuDept()">
                <option value="">-- Sélectionnez un département --</option>
                <?php
                    $departementsAffichees = [];
                    if ($meteotheque->getTypeLocalisation() == "departement"){
                        $localisationActuelle = $meteotheque->getlocalisation();
                    } else {
                        $localisationActuelle = null;
                    }
                    foreach ($stations as $station) {
                        $departement = htmlspecialchars($station->getNom_dept());
                        if (!in_array($departement, $departementsAffichees) && $departement != "il y a rien") {
                            if($localisationActuelle){
                                $selected = ($departement === $localisationActuelle) ? "selected" : "";
                                echo "<option value='$departement' $selected>$departement</option>";
                            } else {
                                echo "<option value='$departement'>$departement</option>";
                            }
                            $departementsAffichees[] = $departement;
                        }
                    }
                ?>
            </select>
        </div>

        <input type="hidden" id="type_localisation" name="type_localisation" value="">
        <?php
            echo '<input type="hidden" id="id_meteotheque" name="id_meteotheque" value="'.htmlspecialchars($meteotheque->getId_meteotheque()).'">';
        ?>

        <div class="form-group">
            <label for="interval-type">Type d'intervalle :</label>
            <select id="interval-type" onchange="updateDateInputs()">
                <option value="">Personnalisé</option>
                <option value="semaine">Semaine</option>
                <option value="mois">Mois</option>
                <option value="saison">Saison</option>
                <option value="annee">Année</option>
            </select>
        </div>

        <!-- Sélection de semaine -->
        <div id="semaine-inputs" class="date-inputs form-group">
            <label for="semaine">Sélectionner une semaine :</label>
            <input type="week" id="semaine" onchange="calculerDates()">
        </div>

        <!-- Sélection de mois -->
        <div id="mois-inputs" class="date-inputs form-group">
            <label for="mois">Sélectionner un mois :</label>
            <input type="month" id="mois" onchange="calculerDates()">
        </div>

        <!-- Sélection de saison -->
        <div id="saison-inputs" class="date-inputs form-group">
            <label for="saison">Sélectionner une saison :</label>
            <select id="saison" onchange="calculerDates()">
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
        <div id="annee-inputs" class="date-inputs form-group">
            <label for="annee">Sélectionner une année :</label>
            <select id="annee" onchange="calculerDates()">
                <?php
                for ($annee = $anneeActuelle - 5; $annee <= $anneeActuelle + 5; $annee++) {
                    echo "<option value='$annee'>$annee</option>";
                }
                ?>
            </select>
        </div>

        <!-- Dates personnalisées -->
        <div id="custom-inputs" class="date-inputs form-group">
            <label for="date_debut">Date de début :</label>
            <input type="date" id="date_debut" name="date_debut" pattern="\d{4}-\d{2}-\d{2}" <?php echo 'value="'.$meteotheque->getDateDebut().'"' ?> required>

            <label for="date_fin">Date de fin :</label>
            <input type="date" id="date_fin" name="date_fin" pattern="\d{4}-\d{2}-\d{2}" <?php echo 'value="'.$meteotheque->getDateFin().'"' ?> required>
        </div>

        <div class="form-group">
            <label for="prive">Privé :</label>
            <input type="checkbox" id="prive" name="prive" value="1" <?php echo $meteotheque->getPrive() ? 'checked' : '' ?>>
        </div>

        <button type="submit">Mettre à jour la météothèque</button>
    </form>
            </div>
</body>
</html>
