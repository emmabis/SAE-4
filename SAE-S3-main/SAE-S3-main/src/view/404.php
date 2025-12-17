<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
        <title><?php echo htmlspecialchars($pagetitle, ENT_QUOTES, 'UTF-8'); ?></title>
        <link rel="stylesheet" href="../web/assets/css/list.css">
        <script src="../web/assets/js/carousel.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body>
        <header>
            <nav>
                <ul>
                    <li><a href="../web/frontController.php?action=readAll&controller=station">Accueil des stations</a></li>
                    <li><a href="../web/frontController.php?action=rechercheStation&controller=station">Recherche station</a></li>
                    <li><a href="../web/frontController.php?action=readAll&controller=meteotheque">Meteotheques</a></li>
                </ul>   
</nav>
</header>

<h1 id='le404e'>Probleme, cette page n'existe pas !<h1>