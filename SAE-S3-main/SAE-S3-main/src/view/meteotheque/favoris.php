<?php
    use App\SAE3\Lib\ConnexionUtilisateur;
    use App\SAE3\model\Repository\FavorisRepository;
    foreach($favoris as $meteotheque){
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
                    <a href="../web/frontController.php?action=setFavori&controller=meteotheque&id_meteotheque=' . $UrlID . '&page=AllFavori" class="heart-link" target="_blank" aria-label="Lien vers example.com">
                        <img src="../web/assets/img/coeur-contour.png" alt="Cœur" class="'.$coeur.'">
                    </a>
                </div>
            </div>
            <p class="pmetheoteque">Localisation : '.htmlspecialchars($meteotheque->getLocalisation()).'</p>
            <p class="pmetheoteque">Date debut : '.htmlspecialchars($meteotheque->getDateDebut()).'</p>
            <p class="pmetheoteque">Date fin : '.htmlspecialchars($meteotheque->getDateFin()).'</p>
            <a class="ametheoteque" href="../web/frontController.php?action=read&controller=meteotheque&id_meteotheque=' . $UrlID . '">Consulter</a>
            <a class="ametheoteque" href="../web/frontController.php?action=delete&controller=meteotheque&id_meteotheque=' . $UrlID . '">Supprimer</a>
            <a class="ametheoteque" href="../web/frontController.php?action=update&controller=meteotheque&id_meteotheque=' . $UrlID . '">Mettre à jour</a>
            </div>';
        } else if (!$estPrive){
            echo '<div class="meteotheque">
            <div class="heart-text">
                <h3 class="h3metheoteque">'.$titre.'</h3>
                <div class="heart-container">
                    <a href="../web/frontController.php?action=setFavori&controller=meteotheque&id_meteotheque=' . $UrlID . '&page=AllFavori" class="heart-link" target="_blank" aria-label="Lien vers example.com">
                        <img src="../web/assets/img/coeur-contour.png" alt="Cœur" class="'.$coeur.'">
                    </a>
                </div>
            </div>
            <p class="pmetheoteque">Localisation : '.htmlspecialchars($meteotheque->getLocalisation()).'</p>
            <p class="pmetheoteque">Date debut : '.htmlspecialchars($meteotheque->getDateDebut()).'</p>
            <p class="pmetheoteque">Date fin : '.htmlspecialchars($meteotheque->getDateFin()).'</p>
            <a class="ametheoteque" href="../web/frontController.php?action=read&controller=meteotheque&id_meteotheque=' . $UrlID . '">Consulter</a>';
            if (ConnexionUtilisateur::estUtilisateur($meteotheque->getUtilisateur()) || ConnexionUtilisateur::estAdministrateur()){
                echo '<a class="ametheoteque" href="../web/frontController.php?action=delete&controller=meteotheque&id_meteotheque=' . $UrlID . '">Supprimer</a>
                <a class="ametheoteque" href="../web/frontController.php?action=update&controller=meteotheque&id_meteotheque=' . $UrlID . '">Mettre à jour</a>';
            }
            echo '</div>';
        }
    }
?>