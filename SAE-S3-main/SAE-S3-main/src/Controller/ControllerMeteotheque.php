<?php

namespace App\SAE3\Controller;

use App\SAE3\model\Repository\MeteoRepository;
use App\SAE3\model\Repository\StationRepository;
use App\SAE3\model\DataObject\Meteotheque;
use App\SAE3\model\Repository\MeteothequeRepository;
use App\SAE3\Lib\ConnexionUtilisateur;
use App\SAE3\Lib\MeteothequeDefaut;
use App\SAE3\Controller\GenericController;
use App\SAE3\model\DataObject\Favoris;
use App\SAE3\model\Repository\FavorisRepository;

class ControllerMeteotheque extends GenericController {
    
    public static function readAll(){
        $meteotheques = (new MeteothequeRepository())->selectAll();
        self::afficheVue('meteotheque/list.php', ['meteotheques' => $meteotheques], 'Formulaire meteotheque');
    }

    public static function read(){
        if (isset($_GET['id_meteotheque'])){
            $id_meteotheque = $_GET['id_meteotheque'];
            $meteothequeChoisi = (new MeteothequeRepository())->select($id_meteotheque);
            if ($meteothequeChoisi instanceof Meteotheque){
                $estPrive = $meteothequeChoisi->getPrive();
                if (!$estPrive || ConnexionUtilisateur::getLoginUtilisateurConnecte()==$meteothequeChoisi->getUtilisateur()||ConnexionUtilisateur::estAdministrateur()){
                    $dateDebut = new \DateTime($meteothequeChoisi->getDateDebut());
                    $dateFin = new \DateTime($meteothequeChoisi->getDateFin());
                    $DataMeteoJour = (new MeteoRepository())->fetchMeteoDataMeteotheque($meteothequeChoisi->getLocalisation(),$dateDebut->format('Y-m-d'),$dateFin->format('Y-m-d'),$meteothequeChoisi->getTypeLocalisation());
                    $DataMeteoJourT21 = array_filter($DataMeteoJour, function ($data) {
                        return strpos($data->getDate(), 'T21') !== false;
                    });
                    self::afficheVue('meteotheque/details.php', ['meteotheque' => $meteothequeChoisi,'DataMeteotheque'=>$DataMeteoJourT21], 'Formulaire meteotheque');
                } else {
                    self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "warning", "Vous n'avez pas les permissions.");
                }
            }
        } else {
            self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "warning", "Veuillez choisir une meteotheque.");
        }
    }

    public static function create(){
        $stations = (new StationRepository())->selectAll();
        self::afficheVue('meteotheque/CreationMeteotheque.php', ['stations' => $stations], 'Formulaire meteotheque');
    }

    public static function created() {
        if (ConnexionUtilisateur::getLoginUtilisateurConnecte()) {
            $date_debut = new \DateTime($_POST['date_debut']);
            $date_fin = new \DateTime($_POST['date_fin']);
    
            $interval = $date_debut->diff($date_fin);
    
            if ($interval->days <= 7 && $interval->invert === 0) {
                $nvMeteotheque = Meteotheque::construireDepuisFormulaire($_POST, ConnexionUtilisateur::getLoginUtilisateurConnecte());
                (new MeteothequeRepository())->sauvegarder($nvMeteotheque);
                self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "success", "Meteotheque créée !");
            } else {
                self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "warning", "La période doit être limitée à 7 jours maximum.");
            }
        } else {
            self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "warning", "Veuillez vous connecter.");
        }
    }
    

    public static function delete(){
        if($_GET['id_meteotheque']){
            $id_meteotheque = $_GET['id_meteotheque'];
            $MeteothequeSelected = (new MeteothequeRepository())->select($id_meteotheque);

            if ($MeteothequeSelected instanceof Meteotheque){
                if(ConnexionUtilisateur::estUtilisateur($MeteothequeSelected->getUtilisateur()) || ConnexionUtilisateur::estAdministrateur()){
                    (new MeteothequeRepository())->Supprimer($id_meteotheque);
                    self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "success", "Meteotheque supprimer !");
                } else {
                    self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "warning", "Vous n'avez pas les permissions.");
                }
            }
            
        }
    }

    public static function update(){
        if ($_GET['id_meteotheque']){
            $id_meteotheque = $_GET['id_meteotheque'];
            $MeteothequeSelected = (new MeteothequeRepository())->select($id_meteotheque);
            if($MeteothequeSelected instanceof Meteotheque){
                if (ConnexionUtilisateur::estUtilisateur($MeteothequeSelected->getUtilisateur())||ConnexionUtilisateur::estAdministrateur()){
                    $stations = (new StationRepository())->selectAll();
                    self::afficheVue('meteotheque/update.php', ['meteotheque'=>$MeteothequeSelected,'stations'=>$stations], 'Mettre à jour une meteotheque');
                } else {
                    self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "warning", "Vous n'avez pas les permissions.");
                }
            }
        }
    }

    public static function updated(){
        if($_POST){
            $MeteothequeSelected = (new MeteothequeRepository())->select($_POST['id_meteotheque']);
            if($MeteothequeSelected instanceof Meteotheque){
                if(ConnexionUtilisateur::estUtilisateur($MeteothequeSelected->getUtilisateur()) || ConnexionUtilisateur::estAdministrateur()){
                    $date_debut = new \DateTime($_POST['date_debut']);
                    $date_fin = new \DateTime($_POST['date_fin']);
                    $interval = $date_debut->diff($date_fin);

                    if ($interval->days <= 7 && $interval->invert === 0) {
                        $meteotheque = new Meteotheque($_POST['titre'], $_POST['localisation'], $_POST['date_debut'], $_POST['date_fin'], $MeteothequeSelected->getUtilisateur(), isset($_POST['prive']) ? 1 : 0, $_POST['type_localisation'] ?? null, $_POST['id_meteotheque']);
                        (new MeteothequeRepository)->update($meteotheque);
                        self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "success", "Meteotheque mis à jour !");
                    } else {
                        self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "warning", "La période doit être limitée à 7 jours maximum.");
                    }

                } else {
                    self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "danger", "Vous n'êtes pas supposer pouvoir modifier.");
                }
            }
        }
    }

    public static function setFavori(){
        $page = $_GET['page']??'readAll';
        $controller="meteotheque";
        $login =$_GET['login'];
        if(isset($login) && $page=='read'){
            $page = "read&login=$login";
            $controller="utilisateur";
        }
        if ($_GET['id_meteotheque']){
            $id_meteotheque = $_GET['id_meteotheque'];
            if(ConnexionUtilisateur::estConnecte()){
                $MeteothequeSelected = (new MeteothequeRepository())->select($id_meteotheque);
                if ($MeteothequeSelected && $MeteothequeSelected instanceof Meteotheque){
                    $estPrive = $MeteothequeSelected->getPrive();
                    if($estPrive && ConnexionUtilisateur::estUtilisateur($MeteothequeSelected->getUtilisateur()) || ConnexionUtilisateur::estAdministrateur()){
                        $conditions = ['utilisateur' => ConnexionUtilisateur::getLoginUtilisateurConnecte(),'id_meteotheque' => $id_meteotheque];
                        $VerifFavori = (new FavorisRepository())->selectAllWhereMultiple($conditions);
                        if(!$VerifFavori){
                            $favori = (new Favoris($id_meteotheque,ConnexionUtilisateur::getLoginUtilisateurConnecte()));
                            (new FavorisRepository())->sauvegarder($favori);
                            self::rediriger("../web/frontController.php?action=$page&controller=$controller", "", "");
                        } else {
                            (new FavorisRepository())->deleteWhereMultiple($conditions);
                            self::rediriger("../web/frontController.php?action=$page&controller=$controller", "", "");
                        }
                    } elseif(!$estPrive){
                        $conditions = ['utilisateur' => ConnexionUtilisateur::getLoginUtilisateurConnecte(),'id_meteotheque' => $id_meteotheque];
                        $VerifFavori = (new FavorisRepository())->selectAllWhereMultiple($conditions);
                        if(!$VerifFavori){
                            $favori = (new Favoris($id_meteotheque,ConnexionUtilisateur::getLoginUtilisateurConnecte()));
                            (new FavorisRepository())->sauvegarder($favori);
                            self::rediriger("../web/frontController.php?action=$page&controller=$controller", "", "");
                        } else {
                            (new FavorisRepository())->deleteWhereMultiple($conditions);
                            self::rediriger("../web/frontController.php?action=$page&controller=$controller", "", "");
                        }
                    } else {
                        self::rediriger("../web/frontController.php?action=$page&controller=$controller", "warning", "Vous n'avez pas les permissions.");
                    }
                } else {
                    self::rediriger("../web/frontController.php?action=$page&controller=$controller", "warning", "La meteotheque n'existe pas.");
                } 
            } else {
                self::rediriger("../web/frontController.php?action=$page&controller=readAll", "warning", "Veuillez vous connecter.");
            }
        } else {
            self::rediriger("../web/frontController.php?action=$page&controller=$controller", "warning", "Veuillez choisir une meteotheque.");
        }
    }

    public static function AllFavori(){
        if(ConnexionUtilisateur::estConnecte()){
            $favorisRecup = (new FavorisRepository())->selectAllWhere('utilisateur',ConnexionUtilisateur::getLoginUtilisateurConnecte());
            $favoris = [];
            foreach($favorisRecup as $meteotheque){
                $meteothequeRecup = (new MeteothequeRepository())->select($meteotheque->getIdMeteotheque());
                
                if ($meteothequeRecup !== null) {
                    $favoris[] = $meteothequeRecup;
                }
            }
            self::afficheVue('meteotheque/favoris.php', ['favoris' => $favoris], 'Liste favoris');
        }
    }

    public static function defaut(){
        if ($_GET['id_meteotheque']){
            $id_meteotheque = $_GET['id_meteotheque'];
            $MeteothequeSelected = (new MeteothequeRepository())->select($id_meteotheque);
                if ($MeteothequeSelected && $MeteothequeSelected instanceof Meteotheque){
                    $estPrive = $MeteothequeSelected->getPrive();
                    if($estPrive && ConnexionUtilisateur::estUtilisateur($MeteothequeSelected->getUtilisateur()) || ConnexionUtilisateur::estAdministrateur()){
                        if(MeteothequeDefaut::existe()){
                            MeteothequeDefaut::supprimer();
                            MeteothequeDefaut::enregistrer($id_meteotheque);
                            self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "success", "Meteotheque mis par defaut");
                        } else {
                            MeteothequeDefaut::enregistrer($id_meteotheque);
                            self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "success", "Meteotheque mis par defaut");
                        }
                    } elseif(!$estPrive){
                        if(MeteothequeDefaut::existe()){
                            MeteothequeDefaut::supprimer();
                            MeteothequeDefaut::enregistrer($id_meteotheque);
                            self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "success", "Meteotheque mis par defaut");
                        } else {
                            MeteothequeDefaut::enregistrer($id_meteotheque);
                            self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "success", "Meteotheque mis par defaut");
                        }
                    } else {
                        self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "warning", "Vous n'avez pas les permissions.");
                    }
                } else {
                    self::rediriger("../web/frontController.php?action=readAll&controller=meteotheque", "warning", "La meteotheque n'existe pas.");
                }
        }
    }

    public static function test(){
        $testData = (new MeteoRepository())->fetchMeteoDataMeteotheque("Guyane","2018-01-16","2018-01-23","region");
        self::afficheVue('meteotheque/test.php', ['testData' => $testData], 'Formulaire meteotheque');
    }
}
?>