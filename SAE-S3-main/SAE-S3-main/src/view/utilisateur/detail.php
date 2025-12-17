<body class='detailmetheoteque-body'>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
    use App\SAE3\Lib\ConnexionUtilisateur;
    use App\SAE3\model\Repository\FavorisRepository;
    echo "<h1 class='titremeteo'>".htmlspecialchars("Profil de " . $utilisateur->getLogin())."</h1>";
    echo "</br>";
    if (ConnexionUtilisateur::estUtilisateur($utilisateur->getLogin())||ConnexionUtilisateur::estAdministrateur()){
        $utilisateurUrl = rawurlencode($utilisateur->getLogin()); // Pour les URLs
        $utilisateurHtml = htmlspecialchars($utilisateur->getLogin(), ENT_QUOTES, 'UTF-8'); // Pour le HTML
        echo '<div class="profile-actions">
        <a class="adetailmediatheque" href="../web/frontController.php?action=delete&controller=utilisateur&login=' . $utilisateurUrl . '">
            Supprimer ' . $utilisateurHtml . '
        </a>
        <a class="adetailmediatheque" href="../web/frontController.php?action=create&controller=meteotheque">
            Créer une méteotheque
        </a>
        <a class="adetailmediatheque" href="../web/frontController.php?action=update&controller=utilisateur&login=' . $utilisateurUrl . '">
            Mettre à jour ' . $utilisateurHtml . '
        </a>
     </div>';
    }
    if (ConnexionUtilisateur::estUtilisateur($utilisateur->getLogin()) && $MeteothequeUser){
        echo '<h2 class="titremeteo">Mes météotheques</h2>';
    } else if($MeteothequeUser){
        echo "<h2 class='titremeteo'>Meteotheques de ". htmlspecialchars($utilisateur->getLogin())."</h2>";
    } else {
        echo "<h2>L'utilisateur n'a pas créer de meteotheque.</h2>";
    }
    foreach($MeteothequeUser as $meteotheque){
        $titre = htmlspecialchars($meteotheque->getTitre());
        $UrlID = rawurlencode($meteotheque->getId_meteotheque());
        $estPrive = $meteotheque->getPrive();
        $conditions = ['utilisateur' => ConnexionUtilisateur::getLoginUtilisateurConnecte(),'id_meteotheque' => $meteotheque->getId_meteotheque()];
        $favori=(new FavorisRepository())->selectAllWhereMultiple($conditions);
        if (!empty($favori)){
          $coeur = "heart-active";
        } else {
          $coeur = "heart";
        }
        if ($estPrive && ConnexionUtilisateur::estUtilisateur($meteotheque->getUtilisateur()) || ConnexionUtilisateur::estAdministrateur()){
            echo '<div class="meteotheque">
            <div class="heart-text">
                <h3 class="h3metheoteque">'.$titre.'</h3>
                <div class="heart-container">
                    <a href="../web/frontController.php?action=setFavori&controller=meteotheque&id_meteotheque=' . $UrlID . '&page=read&login='.$utilisateur->getLogin().'" class="heart-link" aria-label="Lien vers example.com">
                        <img src="../web/assets/img/coeur-contour.png" alt="Cœur" class="'.$coeur.'">
                    </a>
                </div>
            </div>
            <p class="pmetheoteque">Localisation : '.htmlspecialchars($meteotheque->getLocalisation()).'</p>
            <p class="pmetheoteque">Date debut : '.htmlspecialchars($meteotheque->getDateDebut()).'</p>
            <p class="pmetheoteque">Date fin : '.htmlspecialchars($meteotheque->getDateFin()).'</p>
            <a class="ametheoteque" href="../web/frontController.php?action=read&controller=meteotheque&id_meteotheque=' . $UrlID . '">consulter</a>
            <a class="ametheoteque" href="../web/frontController.php?action=delete&controller=meteotheque&id_meteotheque=' . $UrlID . '">Supprimer</a>
            <a class="ametheoteque" href="../web/frontController.php?action=update&controller=meteotheque&id_meteotheque=' . $UrlID . '">Mettre à jour</a>
            </div>';
        } else if (!$estPrive){
            echo '<div class="meteotheque">';
            
            if(ConnexionUtilisateur::estConnecte()){
                echo '<div class="heart-text">
                <h3 class="h3metheoteque">'.$titre.'</h3>
                <div class="heart-container">
                    <a href="../web/frontController.php?action=setFavori&controller=meteotheque&id_meteotheque=' . $UrlID . '&page=read&login='.$utilisateur->getLogin().'" class="heart-link" aria-label="Lien vers example.com">
                        <img src="../web/assets/img/coeur-contour.png" alt="Cœur" class="'.$coeur.'">
                    </a>
                </div>
            </div>';
            }

            echo '<p class="pmetheoteque">Localisation : '.htmlspecialchars($meteotheque->getLocalisation()).'</p>
            <p class="pmetheoteque">Date debut : '.htmlspecialchars($meteotheque->getDateDebut()).'</p>
            <p class="pmetheoteque">Date fin : '.htmlspecialchars($meteotheque->getDateFin()).'</p>
            <a class="ametheoteque" href="../web/frontController.php?action=read&controller=meteotheque&id_meteotheque=' . $UrlID . '">consulter</a>';
            if (ConnexionUtilisateur::estUtilisateur($meteotheque->getUtilisateur()) || ConnexionUtilisateur::estAdministrateur()){
                echo '<a class="ametheoteque" href="../web/frontController.php?action=delete&controller=meteotheque&id_meteotheque=' . $UrlID . '">Supprimer</a>
            <a class="ametheoteque" href="../web/frontController.php?action=update&controller=meteotheque&id_meteotheque=' . $UrlID . '">Mettre à jour</a>';
            }
            echo '</div>';
        }
    }
?></body>