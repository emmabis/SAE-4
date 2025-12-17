<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récupérer la Région</title>
</head>
<body>
    <h2>Récupérer la région à partir de la géolocalisation</h2>
    <button onclick="getLocation()">Obtenir ma localisation</button>

    <div id="result"></div>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                // Demande la position de l'utilisateur
                navigator.geolocation.getCurrentPosition(sendLocation, showError);
            } else {
                document.getElementById("result").innerHTML = "La géolocalisation n'est pas supportée par votre navigateur.";
            }
        }

        // Si la localisation est récupérée avec succès
        function sendLocation(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // Affichage des coordonnées dans la page
            document.getElementById("result").innerHTML = "Latitude: " + latitude + "<br>Longitude: " + longitude;

            // Envoi des coordonnées au serveur PHP
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "geolocalisation-region.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Envoi des données (latitude et longitude)
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Affichage de la réponse (la région)
                    document.getElementById("result").innerHTML += "<br>" + xhr.responseText;
                }
            };

            xhr.send("latitude=" + latitude + "&longitude=" + longitude);
        }

        // Si une erreur survient lors de la récupération de la position
        function showError(error) {
            var errorMessage = "";
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMessage = "L'utilisateur a refusé la demande de géolocalisation.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMessage = "Les informations de géolocalisation sont indisponibles.";
                    break;
                case error.TIMEOUT:
                    errorMessage = "La demande de géolocalisation a expiré.";
                    break;
                case error.UNKNOWN_ERROR:
                    errorMessage = "Une erreur inconnue est survenue.";
                    break;
            }
            document.getElementById("result").innerHTML = errorMessage;
        }
    </script>
</body>
</html>
