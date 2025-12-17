<?php
// In your controller
use App\SAE3\model\DataObject\MeteoGranularite;

// Use the $valeurs object passed from the controller
if (isset($valeurs) && $valeurs !== null) {
    ?>
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Dashboard Météo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>:root {
    --primary-color: #4a6fa5;
    --secondary-color: #166088;
    --accent-color: #4ecdc4;
    
    --card-bg: #27293d;
    --text-color: #ffffff;
    --text-secondary: #cbd5e0;
    --success-color: #00d68f;
    --warning-color: #ffb648;
    --danger-color: #ff5e5e;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: var(--text-color);
    line-height: 1.6;
    min-height: 100vh;
    display: block;
    padding: 1rem;
    margin-bottom: 5%;
}

.dashboard-container {
    margin-top: 15%;
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
    padding: 1rem;
    background-color: transparent;
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.87);
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 1;
}

.dashboard-header {
    grid-column: 1 / -1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.dashboard-header h1 {
    font-size: clamp(1.5rem, 4vw, 1.8rem);
    font-weight: 600;
}

.dashboard-header .date {
    font-size: clamp(0.9rem, 3vw, 1.1rem);
    opacity: 0.8;
}

.card {
    background-color: var(--card-bg);
    border-radius: 10px;
    padding: clamp(1rem, 3vw, 1.5rem);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(to right, var(--primary-color), var(--accent-color));
}

.card-temperature::before {
    background: linear-gradient(to right, #ff5e5e, #ff8f70);
}

.card-humidity::before {
    background: linear-gradient(to right, #4ecdc4, #26c6da);
}

.card-pressure::before {
    background: linear-gradient(to right, #a389ff, #5c6bc0);
}

.card-nebulosity::before {
    background: linear-gradient(to right, #7e57c2, #5c6bc0);
}

.card-visibility::before {
    background: linear-gradient(to right, #66bb6a, #43a047);
}

.card-station-pressure::before {
    background: linear-gradient(to right, #ffca28, #ffa726);
}

.card-pressure-variation::before {
    background: linear-gradient(to right, #ec407a, #d81b60);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.card-title {
    font-size: clamp(0.9rem, 3vw, 1.1rem);
    font-weight: 500;
    color: var(--text-secondary);
}

.card-icon {
    font-size: clamp(1.2rem, 3vw, 1.5rem);
    width: clamp(40px, 8vw, 50px);
    height: clamp(40px, 8vw, 50px);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.1);
    color: var(--accent-color);
    flex-shrink: 0;
}

.card-value {
    font-size: clamp(1.8rem, 5vw, 2.2rem);
    font-weight: 700;
    margin: 0.5rem 0;
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
}

.card-unit {
    font-size: clamp(0.9rem, 2.5vw, 1rem);
    margin-left: 0.5rem;
    color: var(--text-secondary);
}

.card-footer {
    display: flex;
    align-items: center;
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
    color: var(--text-secondary);
}

.trend-up {
    color: var(--success-color);
}

.trend-down {
    color: var(--danger-color);
}

.trend-stable {
    color: var(--warning-color);
}

.trend-icon {
    margin-right: 0.5rem;
}

.value-conversion {
    color: var(--text-secondary);
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
    margin: -0.3rem 0 0.5rem 0;
    font-style: italic;
}

.temp-minmax {
    display: flex;
    justify-content: space-between;
    margin: 0.5rem 0;
    padding: 0.5rem;
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 5px;
    font-size: clamp(0.8rem, 2.5vw, 0.9rem);
}

.temp-min {
    color: #26c6da;
}

.temp-max {
    color: #ff5e5e;
}

/* Mobile first approach with progressive enhancements */
@media (max-width: 576px) {
    body {
        padding: 0.5rem;
    }
    
    .dashboard-container {
        grid-template-columns: 1fr;
        gap: 0.75rem;
        padding: 0.75rem;
    }
    
    .card {
        padding: 1rem;
    }
}

/* Small devices (landscape phones) */
@media (min-width: 576px) and (max-width: 767px) {
    .dashboard-container {
        grid-template-columns: repeat(1, 1fr);
    }
}

/* Medium devices (tablets) */
@media (min-width: 768px) and (max-width: 991px) {
    .dashboard-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Large devices (desktops) */
@media (min-width: 992px) and (max-width: 1199px) {
    .dashboard-container {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Extra large devices */
@media (min-width: 1200px) {
    .dashboard-container {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* Print styles for better printing */
@media print {
    body {
        background: none;
        color: #000;
    }
    
    .dashboard-container {
        box-shadow: none;
        background: none;
        width: 100%;
    }
    
    .card {
        break-inside: avoid;
        background: #f8f9fa;
        color: #212529;
    }
    
    .card-title, .card-unit, .card-footer, .value-conversion {
        color: #495057;
    }
}
    </style>
</head>
<body class="infogranuanto">
    <div class="dashboard-container">
        
        <!-- Temperature Card -->
        <div class="card card-temperature">
            <div class="card-header">
                <h2 class="card-title">Température</h2>
                <div class="card-icon">
                    <i class="fas fa-temperature-high"></i>
                </div>
            </div>
            <div class="card-value">
                <?php echo htmlspecialchars(substr($valeurs->getTemperature(), 0,6) ?? "N/A"); ?>
                <span class="card-unit">°C</span>
            </div>
            <?php if (method_exists($valeurs, 'getTemperatureMin') && method_exists($valeurs, 'getTemperatureMax')): ?>
            <div class="temp-minmax">
                <span class="temp-min">
                    <i class="fas fa-arrow-down"></i> 
                    Min: <?php echo htmlspecialchars($valeurs->getTemperatureMin() ?? "N/A"); ?>°C
                </span>
                <span class="temp-max">
                    <i class="fas fa-arrow-up"></i> 
                    Max: <?php echo htmlspecialchars($valeurs->getTemperatureMax()?? "N/A"); ?>°C
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Humidity Card -->
        <div class="card card-humidity">
            <div class="card-header">
                <h2 class="card-title">Humidité</h2>
                <div class="card-icon">
                    <i class="fas fa-tint"></i>
                </div>
            </div>
            <div class="card-value">
                <?php echo htmlspecialchars(substr($valeurs->getHumidite(), 0, 6) ?? "N/A"); ?>
                <span class="card-unit">%</span>
            </div>
            <?php if (method_exists($valeurs, 'getHumiditeMin') && method_exists($valeurs, 'getHumiditeMax')): ?>
            <div class="temp-minmax">
                <span class="temp-min">
                    <i class="fas fa-arrow-down"></i> 
                    Min: <?php echo htmlspecialchars($valeurs->getHumiditeMin() ?? "N/A"); ?>%
                </span>
                <span class="temp-max">
                    <i class="fas fa-arrow-up"></i> 
                    Max: <?php echo htmlspecialchars($valeurs->getHumiditeMax() ?? "N/A"); ?>%
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Pressure Card -->
        <div class="card card-pressure">
            <div class="card-header">
                <h2 class="card-title">Pression atmosphérique</h2>
                <div class="card-icon">
                    <i class="fas fa-compress-alt"></i>
                </div>
            </div>
            <div class="card-value">
                <?php echo htmlspecialchars(substr($valeurs->getPression() / 100, 0, 6) ?? "N/A"); ?>
                <span class="card-unit">hPa</span>
            </div>
            <div class="value-conversion">
                <?php echo htmlspecialchars(($valeurs->getPression() / 100) ?? "N/A"); ?> hPa
            </div>
            <?php if (method_exists($valeurs, 'getPressionMin') && method_exists($valeurs, 'getPressionMax')): ?>
            <div class="temp-minmax">
                <span class="temp-min">
                    <i class="fas fa-arrow-down"></i> 
                    Min: <?php echo htmlspecialchars(($valeurs->getPressionMin() / 100) ?? "N/A"); ?> hPa
                </span>
                <span class="temp-max">
                    <i class="fas fa-arrow-up"></i> 
                    Max: <?php echo htmlspecialchars(($valeurs->getPressionMax() / 100) ?? "N/A"); ?> hPa
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Nebulosity Card -->
        <div class="card card-nebulosity">
            <div class="card-header">
                <h2 class="card-title">Nébulosité totale</h2>
                <div class="card-icon">
                    <i class="fas fa-cloud"></i>
                </div>
            </div>
            <div class="card-value">
                <?php echo htmlspecialchars(substr($valeurs->getNebulositeTotal(), 0, 6) ?? "N/A"); ?>
            </div>
            <?php if (method_exists($valeurs, 'getNebulositeTotalMin') && method_exists($valeurs, 'getNebulositeTotalMax')): ?>
            <div class="temp-minmax">
                <span class="temp-min">
                    <i class="fas fa-arrow-down"></i> 
                    Min: <?php echo htmlspecialchars($valeurs->getNebulositeTotalMin() ?? "N/A"); ?>
                </span>
                <span class="temp-max">
                    <i class="fas fa-arrow-up"></i> 
                    Max: <?php echo htmlspecialchars($valeurs->getNebulositeTotalMax() ?? "N/A"); ?>
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Visibility Card -->
        <div class="card card-visibility">
            <div class="card-header">
                <h2 class="card-title">Visibilité horizontale</h2>
                <div class="card-icon">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
            <div class="card-value">
                <?php echo htmlspecialchars(substr($valeurs->getVisibiliteHorizontale(), 0, 8) ?? "N/A"); ?>
                <span class="card-unit">km</span>
            </div>
            <?php if (method_exists($valeurs, 'getVisibiliteHorizontaleMin') && method_exists($valeurs, 'getVisibiliteHorizontaleMax')): ?>
            <div class="temp-minmax">
                <span class="temp-min">
                    <i class="fas fa-arrow-down"></i> 
                    Min: <?php echo htmlspecialchars($valeurs->getVisibiliteHorizontaleMin() ?? "N/A"); ?> km
                </span>
                <span class="temp-max">
                    <i class="fas fa-arrow-up"></i> 
                    Max: <?php echo htmlspecialchars($valeurs->getVisibiliteHorizontaleMax() ?? "N/A"); ?> km
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Station Pressure Card -->
        <div class="card card-station-pressure">
            <div class="card-header">
                <h2 class="card-title">Pression à la station</h2>
                <div class="card-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
            </div>
            <div class="card-value">
                <?php echo htmlspecialchars(substr($valeurs->getPressionStation() / 100,0 ,6) ?? "N/A"); ?>
                <span class="card-unit">hPa</span>
            </div>
            <div class="value-conversion">
                <?php echo htmlspecialchars((substr($valeurs->getPressionStation() ,0,6)) ?? "N/A"); ?> Pa
            </div>
            <?php if (method_exists($valeurs, 'getPressionStationMin') && method_exists($valeurs, 'getPressionStationMax')): ?>
            <div class="temp-minmax">
                <span class="temp-min">
                    <i class="fas fa-arrow-down"></i> 
                    Min: <?php echo htmlspecialchars(($valeurs->getPressionStationMin() / 100) ?? "N/A"); ?> hPa
                </span>
                <span class="temp-max">
                    <i class="fas fa-arrow-up"></i> 
                    Max: <?php echo htmlspecialchars(($valeurs->getPressionStationMax() / 100) ?? "N/A"); ?> hPa
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Pressure Variation Card -->
        <div class="card card-pressure-variation">
            <div class="card-header">
                <h2 class="card-title">Variation de pression (3h)</h2>
                <div class="card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="card-value">
                <?php 
                // Check which method exists and use the appropriate one
                if (method_exists($valeurs, 'getVariationpression3h')) {
                    echo htmlspecialchars(substr($valeurs->getVariationpression3h() / 100, 0,6) ?? "N/A");
                } else if (method_exists($valeurs, 'getVariationPressionEn3h')) {
                    echo htmlspecialchars(substr($valeurs->getVariationPressionEn3h() / 100,0,6) ?? "N/A");
                } else {
                    echo "N/A";
                }
                ?>
                <span class="card-unit">hPa</span>
            </div>
            <div class="value-conversion">
                <?php 
                $variationValue = null;
                if (method_exists($valeurs, 'getVariationpression3h')) {
                    $variationValue = substr($valeurs->getVariationpression3h(), 0,6);
                } else if (method_exists($valeurs, 'getVariationPressionEn3h')) {
                    $variationValue = substr($valeurs->getVariationPressionEn3h(), 0,6);
                }
                
                if ($variationValue !== null) {
                    echo htmlspecialchars(substr($variationValue ,0,6) ?? "N/A");
                } else {
                    echo "N/A";
                }
                ?> Pa
            </div>
            <?php if ((method_exists($valeurs, 'getVariationpression3hMin') && method_exists($valeurs, 'getVariationpression3hMax'))): ?>
            <div class="temp-minmax">
                <span class="temp-min">
                    <i class="fas fa-arrow-down"></i> 
                    Min: <?php echo htmlspecialchars((substr($valeurs->getVariationpression3hMin() / 100,0,6)) ?? "N/A"); ?> hPa
                </span>
                <span class="temp-max">
                    <i class="fas fa-arrow-up"></i> 
                    Max: <?php echo htmlspecialchars((substr($valeurs->getVariationpression3hMax() / 100,0,6)) ?? "N/A"); ?> hPa
                </span>
            </div>
            <?php endif; ?>
            <div class="card-footer">
                <?php 
                $variation = 0;
                if (method_exists($valeurs, 'getVariationpression3h')) {
                    $variation = substr($valeurs->getVariationpression3h(),0,6) ?? 0;
                } else if (method_exists($valeurs, 'getVariationPressionEn3h')) {
                    $variation = substr($valeurs->getVariationPressionEn3h(),0,6) ?? 0;
                }
                
                if ($variation > 0) {
                    echo '<i class="fas fa-arrow-up trend-icon trend-up"></i>';
                    echo '<span>Tendance à la hausse</span>';
                } elseif ($variation < 0) {
                    echo '<i class="fas fa-arrow-down trend-icon trend-down"></i>';
                    echo '<span>Tendance à la baisse</span>';
                } else {
                    echo '<i class="fas fa-minus trend-icon trend-stable"></i>';
                    echo '<span>Pression stable</span>';
                }
                ?>
            </div>
        </div>
        
        <!-- Wind Speed Card -->
        <div class="card card-wind-speed">
            <div class="card-header">
                <h2 class="card-title">Vitesse du Vent</h2>
                <div class="card-icon">
                    <i class="fas fa-wind"></i>
                </div>
            </div>
            <div class="card-value">
                <?php echo htmlspecialchars(substr($valeurs->getVitesseVent(),0,6) ?? "N/A"); ?>
                <span class="card-unit">km/h</span>
            </div>
            <?php if (method_exists($valeurs, 'getVitesseVentMin') && method_exists($valeurs, 'getVitesseVentMax')): ?>
            <div class="temp-minmax">
                <span class="temp-min">
                    <i class="fas fa-arrow-down"></i> 
                    Min: <?php echo htmlspecialchars(substr($valeurs->getVitesseVentMin(),0,6) ?? "N/A"); ?> km/h
                </span>
                <span class="temp-max">
                    <i class="fas fa-arrow-up"></i> 
                    Max: <?php echo htmlspecialchars(substr($valeurs->getVitesseVentMax(),0,6) ?? "N/A"); ?> km/h
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Wind Direction Card -->
        <div class="card card-wind-direction">
            <div class="card-header">
                <h2 class="card-title">Precipitation</h2>
                <div class="card-icon">
                    <i class="fas fa-location-arrow"></i>
                </div>
            </div>
            <div class="card-value">
                <?php echo htmlspecialchars(substr($valeurs->getPrecipation(),0,6) ?? "N/A"); ?>
                <span class="card-unit">mm</span>
            </div>
            <?php if (method_exists($valeurs, 'getVitesseVentMin') && method_exists($valeurs, 'getVitesseVentMax')): ?>
            <div class="temp-minmax">
                <span class="temp-min">
                    <i class="fas fa-arrow-down"></i> 
                    Min: <?php echo htmlspecialchars(substr($valeurs->getPrecipationMin(),0,6) ?? "N/A"); ?> mm
                </span>
                <span class="temp-max">
                    <i class="fas fa-arrow-up"></i> 
                    Max: <?php echo htmlspecialchars(substr($valeurs->getPrecipationMax(),0,6) ?? "N/A"); ?> mm
                </span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Type of Cloud Card -->
        <div class="card card-cloud-type">
            <div class="card-header">
                <h2 class="card-title">Type de nuage</h2>
                <div class="card-icon">
                    <i class="fas fa-cloud"></i>
                </div>
            </div>
            <div class="card-value" style="font-size: 1.6rem;">
                <?php echo htmlspecialchars(substr($valeurs->getTypeNuage(),0,6) ?? "N/A"); ?>
            </div>
            <div class="card-footer">
                <i class="fas fa-info-circle trend-icon"></i>
                <span>Classification actuelle</span>
            </div>
        </div>
        

        <!-- Current Weather Card -->
        <div class="card card-current-weather">
            <div class="card-header">
                <h2 class="card-title">Temps Présent</h2>
                <div class="card-icon">
                    <i class="fas fa-cloud-sun"></i>
                </div>
            </div>
            <div class="card-value" style="font-size: 1.6rem;">
                <?php 
                $date = $valeurs->getDate() ?? null;
                echo $date ? htmlspecialchars(date('d/m/Y', strtotime($date))) : "N/A"; 
                ?>
            </div>
            <div class="card-footer">
                <i class="fas fa-calendar-day trend-icon"></i>
                <span>Dernière mise à jour</span>
            </div>
        </div>
        
        <!-- Cloud Base Height Card -->
        <div class="card card-cloud-base">
            <div class="card-header">
                <h2 class="card-title">Hauteur Base Nuages</h2>
                <div class="card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="card-value" style="font-size: 1.6rem;">
                <?php echo htmlspecialchars($valeurs->getHauteurBasNuage() ?? "N/A"); ?>
            </div>
            <div class="card-footer">
                <i class="fas fa-info-circle trend-icon"></i>
                <span>Hauteur actuelle</span>
            </div>
        </div>
    </div>
</body>
</html>
    <?php
} else {
    ?>
    <!DOCTYPE html>
    <html lang='fr'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Dashboard Météo - Erreur</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #1e1e2f;
                color: #ffffff;
                line-height: 1.6;
                min-height: 100vh;
                display: block;
                padding-top: 2rem;
            }
            
            .error-container {
                background-color: #27293d;
                border-radius: 10px;
                padding: 2rem;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                text-align: center;
                max-width: 500px;
                width: 90%;
                margin: 3rem auto 0;
            }
            
            .error-icon {
                font-size: 4rem;
                color: #ff5e5e;
                margin-bottom: 1rem;
            }
            
            .error-title {
                font-size: 1.8rem;
                margin-bottom: 1rem;
            }
            
            .error-message {
                color: #cbd5e0;
                margin-bottom: 1.5rem;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <h1 class="error-title">Erreur</h1>
            <p class="error-message">Les données météorologiques ne sont pas disponibles pour le moment. Veuillez réessayer plus tard.</p>
        </div>
    </body>
    </html>
    <?php
}
?>
