<body class='mediatheque-body'>
<?php
use App\SAE3\Lib\ConnexionUtilisateur;
use App\SAE3\model\Repository\FavorisRepository;

foreach($meteotheques as $meteotheque){
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
            <a href="../web/frontController.php?action=setFavori&controller=meteotheque&id_meteotheque=' . $UrlID . '" class="heart-link" aria-label="Lien vers example.com">
              <img src="../web/assets/img/coeur-contour.png" alt="Cœur" class="'.$coeur.'">
            </a>
          </div>
        </div>
        <p class="pmetheoteque">Localisation : '.htmlspecialchars($meteotheque->getLocalisation()).'</p>
        <p class="pmetheoteque">Date debut : '.htmlspecialchars($meteotheque->getDateDebut()).'</p>
        <p class="pmetheoteque">Date fin : '.htmlspecialchars($meteotheque->getDateFin()).'</p>
        <a class="ametheoteque" href="../web/frontController.php?action=read&controller=meteotheque&id_meteotheque=' . $UrlID . '">Consulter</a>
        <a class="ametheoteque" href="../web/frontController.php?action=defaut&controller=meteotheque&id_meteotheque=' . $UrlID . '">Defaut</a>
        <a class="ametheoteque" href="../web/frontController.php?action=delete&controller=meteotheque&id_meteotheque=' . $UrlID . '">Supprimer</a>
        <a class="ametheoteque" href="../web/frontController.php?action=update&controller=meteotheque&id_meteotheque=' . $UrlID . '">Mettre à jour</a>
        </div>';
    } else if (!$estPrive){
      echo '<div class="meteotheque">';
            
      if(ConnexionUtilisateur::estConnecte()){
          echo '<div class="heart-text">
          <h3 class="h3metheoteque">'.$titre.'</h3>
          <div class="heart-container">
              <a href="../web/frontController.php?action=setFavori&controller=meteotheque&id_meteotheque=' . $UrlID . '&page=readAll" class="heart-link" aria-label="Lien vers example.com">
                  <img src="../web/assets/img/coeur-contour.png" alt="Cœur" class="'.$coeur.'">
              </a>
          </div>
      </div>';
      } else {
        echo'<h3 class="h3metheoteque">'.$titre.'</h3>';
      }

      echo '<p class="pmetheoteque">Localisation : '.htmlspecialchars($meteotheque->getLocalisation()).'</p>
        <p class="pmetheoteque">Date debut : '.htmlspecialchars($meteotheque->getDateDebut()).'</p>
        <p class="pmetheoteque">Date fin : '.htmlspecialchars($meteotheque->getDateFin()).'</p>
        <a class="ametheoteque" href="../web/frontController.php?action=read&controller=meteotheque&id_meteotheque=' . $UrlID . '">Consulter</a>
        <a class="ametheoteque" href="../web/frontController.php?action=defaut&controller=meteotheque&id_meteotheque=' . $UrlID . '">Mettre par defaut</a>';
        if (ConnexionUtilisateur::estUtilisateur($meteotheque->getUtilisateur()) || ConnexionUtilisateur::estAdministrateur()){
            echo '<a class="ametheoteque" href="../web/frontController.php?action=delete&controller=meteotheque&id_meteotheque=' . $UrlID . '">Supprimer</a>
        <a class="ametheoteque" href="../web/frontController.php?action=update&controller=meteotheque&id_meteotheque=' . $UrlID . '">Mettre à jour</a>';
        }
        echo '</div>';
    }
}
?>
</body>

<style>
.meteotheque {
  border-radius: 15px;
  background-color: rgba(0, 0, 0, 0.5);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  margin: 20px auto;
  padding: 20px;
  max-width: 600px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.meteotheque:hover {
  transform: scale(1.02);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.h3metheoteque {
  font-size: 1.8em;
  color: #fff;
  margin-bottom: 10px;
  font-weight: bold;
  text-decoration: underline;
}

.pmetheoteque {
  font-size: 1.1em;
  color: #ddd;
  margin: 8px 0;
}

.ametheoteque {
  display: inline-block;
  margin-top: 15px;
  padding: 10px 20px;
  font-size: 1em;
  text-decoration: none;
  color: #fff;
  background-color: rgba(189, 130, 2, 0.8);
  border-radius: 20px;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.ametheoteque:hover {
  background-color: rgba(240, 165, 0, 0.9);
  transform: translateY(-3px);
}
</style>
