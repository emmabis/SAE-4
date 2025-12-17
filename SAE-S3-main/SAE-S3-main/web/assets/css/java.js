document.addEventListener("DOMContentLoaded", function () {
        const stations = document.querySelectorAll(".station");
        const prevButton = document.getElementById("prev");
        const nextButton = document.getElementById("next");
        let currentIndex = 0;

        // Fonction pour mettre à jour l'affichage
        function updateDisplay() {
            stations.forEach((station, index) => {
                station.style.display = index === currentIndex ? "block" : "none";
            });

            // Activer ou désactiver les boutons
            prevButton.disabled = currentIndex === 0;
            nextButton.disabled = currentIndex === stations.length - 1;
        }

        // Gestion des clics sur les boutons
        prevButton.addEventListener("click", () => {
            if (currentIndex > 0) currentIndex--;
            updateDisplay();
        });

        nextButton.addEventListener("click", () => {
            if (currentIndex < stations.length - 1) currentIndex++;
            updateDisplay();
        });

        // Initialiser l'affichage
        updateDisplay();
    });