document.addEventListener('DOMContentLoaded', () => {
    let currentIndex = 0;
    const slides = document.querySelectorAll('.slide');

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
    }


    showSlide(currentIndex);

    document.getElementById('prev').addEventListener('click', () => {
        currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
        showSlide(currentIndex);
    });

    document.getElementById('next').addEventListener('click', () => {
        currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
        showSlide(currentIndex);
    });
});


document.addEventListener('DOMContentLoaded', () => {
    // Récupération des données transmises dans le HTML via des attributs `data-*`
    const labels = JSON.parse(document.getElementById('myChart').dataset.labels);
    const data = JSON.parse(document.getElementById('myChart').dataset.values);

    // Configuration du graphique
    const config = {
        type: 'line', // Type de graphique : ligne
        data: {
            labels: labels, // Les étiquettes (mois)
            datasets: [{
                label: 'Températures (°C)', // Légende
                data: data, // Les températures
                fill: false, // Pas de remplissage sous la ligne
                borderColor: 'rgba(75, 192, 192, 1)', // Couleur de la ligne
                tension: 0.3, // Courbure de la ligne (0 pour droite, 0.3 pour arrondi)
                pointBackgroundColor: 'rgba(75, 192, 192, 1)', // Couleur des points
                pointBorderColor: '#fff', // Bordure des points
                pointRadius: 5 // Taille des points
            }]
        },
        options: {
            responsive: true, // Le graphique s'adapte à l'écran
            plugins: {
                legend: {
                    display: true, // Affiche la légende
                    position: 'top'
                }
            },
            scales: {
                x: { // Axe X : mois
                    title: {
                        display: true,
                        text: 'Mois'
                    }
                },
                y: { // Axe Y : températures
                    title: {
                        display: true,
                        text: 'Températures (°C)'
                    },
                    beginAtZero: false // Commence à zéro si nécessaire
                }
            }
        }
    };

    // Rendu du graphique dans le canvas
    const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
});
