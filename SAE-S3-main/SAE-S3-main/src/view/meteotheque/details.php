<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Météothèque</title>
    <link rel="stylesheet" href="../web/assets/css/list.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body id="meteotheque">
<div id="toutMeteotheque">
    <div class="MeteoNormal">
    <?php
        foreach($DataMeteotheque as $meteo){
            $date = DateTime::createFromFormat(DateTime::ATOM, $meteo->getDate());
            $date = $date->format("d/m/Y");
            $cheminImage = '../web/assets/img/';
            $image = '';

            if ($meteo->getHumidite() > 80 && $meteo->getnebulositetotal() > 75) {
                $image = 'brouillard.png';
                $tempsDate = 'Brouillard épais';
            } elseif ($meteo->getTemperature() < 0) {
                if ($meteo->getnebulositetotal() > 50) {
                    $image = 'neige_forte.png';
                    $tempsDate = 'Neige abondante';
                } else {
                    $image = 'neige_faible.png';
                    $tempsDate = 'Neige légère';
                }
            } elseif ($meteo->getvariationpressionen3h() < 0) {
                $image = 'pluie.png';
                $tempsDate = 'Pluie';
            } elseif ($meteo->getVitesseVent() > 20) {
                $image = 'vent.png';
                $tempsDate = 'Vent fort';
            } elseif ($meteo->getnebulositetotal() > 75) {
                $image = 'nuageux.png';
                $tempsDate = 'Ciel très nuageux';
            } elseif ($meteo->getnebulositetotal() > 50) {
                $image = 'nuageux_clair.png';
                $tempsDate = 'Ciel partiellement nuageux';
            } elseif ($meteo->getTemperature() > 25) {
                $image = 'nuageux_soleil.png';
                $tempsDate = 'Un peu ensolleilé avec quelques nuages';
            } else {
                $image = 'clair.png';
                $tempsDate = 'Ciel dégagé';
            }
            
            
            echo "<div class=dataMeteotheque>
                <h4>$date</h4>
                <img src='{$cheminImage}{$image}' alt='Condition météorologique' />
                <p>$tempsDate</p>
            </div>";
        }
    ?>
    </div>
    <div id="graphiques">
        <!-- Temperature Chart -->
        <!-- Temperature Chart -->
<div id="Temperature">
    <canvas id="temperatureChart"></canvas>
    <?php
    // Tableau de traduction des jours
    $joursEnFrancais = array(
        'Monday' => 'Lundi',
        'Tuesday' => 'Mardi',
        'Wednesday' => 'Mercredi',
        'Thursday' => 'Jeudi',
        'Friday' => 'Vendredi',
        'Saturday' => 'Samedi',
        'Sunday' => 'Dimanche'
    );

    $labels = [];
    $tempValues = [];
    $humValues = [];
    
    // Date de départ
    $dateDepart = strtotime($meteotheque->getDateDebut());
    
    foreach ($DataMeteotheque as $index => $donnee) {
        if ($donnee !== null) {
            // Calcul de la date pour chaque jour
            $timestamp = strtotime("+$index days", $dateDepart);
            $jourEnAnglais = date("l", $timestamp);
            $labels[] = $joursEnFrancais[$jourEnAnglais];
            $tempValues[] = $donnee->getTemperature() ?? 0;
            $humValues[] = $donnee->getHumidite() ?? 0;
        }
    }
    ?>
    <script>
        const tempLabels = <?php echo json_encode($labels); ?>;
        const tempData = <?php echo json_encode($tempValues); ?>;

        const tempConfig = {
            type: 'line',
            data: {
                labels: tempLabels,
                datasets: [{
                    label: 'Températures (°C)',
                    data: tempData,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.0,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    pointBorderColor: '#000',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: 'white',
                            font: {
                                size: 18,
                                weight: 'bold'
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Jours',
                            color: 'white'
                        },
                        ticks: {
                            color: 'white'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Températures (°C)',
                            color: 'white'
                        },
                        ticks: {
                            color: 'white'
                        },
                        min: Math.min(...tempData) - 1,
                        max: Math.max(...tempData) + 1
                    }
                }
            }
        };

        new Chart(
            document.getElementById('temperatureChart'),
            tempConfig
        );
    </script>
</div>

<!-- Humidity Chart -->
<div id="Humidity">
    <canvas id="humidityChart"></canvas>
    <script>
        const humLabels = <?php echo json_encode($labels); ?>;
        const humData = <?php echo json_encode($humValues); ?>;

        const humConfig = {
            type: 'line',
            data: {
                labels: humLabels,
                datasets: [{
                    label: 'Humidité (%)',
                    data: humData,
                    fill: false,
                    borderColor: 'rgb(173, 88, 19)',
                    tension: 0.0,
                    pointBackgroundColor: 'rgb(173, 88, 19)',
                    pointBorderColor: '#000',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: 'white',
                            font: {
                                size: 18,
                                weight: 'bold'
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Jours',
                            color: 'white'
                        },
                        ticks: {
                            color: 'white'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Humidité (%)',
                            color: 'white'
                        },
                        ticks: {
                            color: 'white'
                        },
                        min: Math.min(...humData) - 1,
                        max: Math.max(...humData) + 1
                    }
                }
            }
        };

        new Chart(
            document.getElementById('humidityChart'),
            humConfig
        );
    </script>
    </div>
    </div>
</div>
</body>
</html>