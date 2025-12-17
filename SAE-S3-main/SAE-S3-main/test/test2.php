<?php

    $latitude = 47.9985579;
    $longitude = 2.7241442;

    // Clé API OpenCage Geocoder (remplacez avec votre propre clé API)
    $apiKey = 'd2aba23543bf43babb8bc51c71a9ea3b'; // Remplacez avec votre clé API OpenCage

    // URL de l'API OpenCage Geocoder pour récupérer les données de géolocalisation inverse
    $url = "https://api.opencagedata.com/geocode/v1/json?q={$latitude}+{$longitude}&key={$apiKey}&language=fr&no_annotations=1";

    // Effectuer la requête à l'API
    $response = file_get_contents($url);

    // Décoder la réponse JSON
    $data = json_decode($response);

    // Vérifier si la réponse est valide
    if ($data && $data->status->code == 200) {
        // Extraire la région ou la zone géographique
        $region = isset($data->results[1]->components->state) ? $data->results[1]->components->state : 'Inconnu';

        echo "Latitude: " . $latitude . "<br>";
        echo "Longitude: " . $longitude . "<br>";
        echo "Région: " . $region . "<br>";
    } else {
        echo "Impossible de récupérer la région pour ces coordonnées.";
    }

?>
