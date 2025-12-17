<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
require __DIR__ . '/rechercheStation.php';
echo "<h1 class='legrostitre'>$statut</h1>";

if ($meteo){
    $infosMeteo = [
        'date' => $meteo->getDate(),
        'Température' => $meteo->getTemperature() . "°C",
        'Humidité' => $meteo->getHumidite() . "%",
        'Pression' => $meteo->getPression() . " hPa",
        'Vitesse du vent' => $meteo->getVitesseVent() . " m/s",
        'Direction du vent' => $meteo->getDirectionVent() . "°",
        'Type de nuage' => $meteo->getTypeNuage(),

        'Nébulosité totale' => $meteo->getnebulositetotal() . "%",
        'Visibilité horizontale' => $meteo->getvisibilitehorizontale() . "m",
        'Hauteur de base des nuages' => $meteo->gethauteurbasenuage() . "m",
        'Pression station' => $meteo->getpressionstation() . "hPa",
        'Variation pression en 3h' => $meteo->getvariationpressionen3h() . "hPa",
        'Type de la tendance barométrique' => $meteo->gettypetendancebarometrique(),
        


        
        'Temps present' => $meteo->getTempsPresent()
    ];
    
    foreach ($infosMeteo as $label => $valeur) {
        echo "<p class='donnees'><strong>{$label}:</strong> {$valeur}</p>";
}
}
?>