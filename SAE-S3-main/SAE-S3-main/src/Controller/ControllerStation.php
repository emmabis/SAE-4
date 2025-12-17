<?php

namespace App\SAE3\Controller;

use App\SAE3\model\Repository\StationRepository;
use App\SAE3\model\Repository\MeteoRepository;

class ControllerStation extends GenericController {

    public static function readAll() {
        $stations = (new StationRepository())->selectAll();
        self::afficheVue('station/list.php', ['stations' => $stations], 'Liste des stations'); // Redirection vers la vue
    }

    public static function regionCarte(){
        $region = $_GET['region'] ?? null;

        if ($region) {
            $departements = (new StationRepository())->selectAllWhere("nom_reg",$region); 
            self::afficheVue('station/infoRegion.php', ['departements' => $departements], 'Liste des stations'); 
        }
    }

    public static function read() {
        $numer_sta = $_GET['numer_sta'] ?? null;
    
        if ($numer_sta) {
            $station = (new StationRepository())->select($numer_sta);
            if (is_object($station)) {
                self::afficheVue('station/detail.php', ['station' => $station], 'Détail station');
            } else {
                self::afficheVue('station/error.php', [], 'Erreur');
            }
        } else {
            self::afficheVue('station/error.php', [], 'Erreur');
        }
    }

    public static function carte() {
        self::afficheVue('station/carte.php', [], 'Carte');
    }

    public static function rechercheStation(){
        self::afficheVue('station/rechercheStation.php', [], 'Recherche station');
    }

    public static function dataMeteo() {
        $id_station = $_POST["station_id"];
        $meteoRepo = new MeteoRepository();
        $meteoData = $meteoRepo->fetchMeteoData($id_station);
       
        if ($meteoData) {
            self::afficheVue('station/meteo.php', ['statut' => 'Météo actuelle','meteo' => $meteoData], 'Météo actuelle');
        } else {
            self::afficheVue('station/meteo.php', ['statut' => 'Erreur aucune donnée','meteo' => $meteoData], 'Météo actuelle');
        }
    }



    public static function readStationDash() {
        $id_station = $_GET["station_id"];
        $stationpardate = [];
        if (isset($_GET['date'])) {
            $datee = $_GET['date'];
            for ($i=0; $i < 7; $i++) { 
                $date = (new \DateTime("$datee"))->modify("-$i day")->format('Y-m-d');
                $stationdate = (new MeteoRepository())->fetchMeteoDataDate("$id_station","$date");
                $stationpardate["$i"] = [$stationdate,$date];
            }
            $stations = (new StationRepository())->selectAll();
            self::afficheVue('station/dashboard.php', ['stations' => $stations, 'stationpardate' => $stationpardate], 'Liste des stations'); // Redirection vers la vue
        }else {
            for ($i=0; $i < 7; $i++) { 
                $date = (new \DateTime("now"))->modify("-$i day")->format('Y-m-d');
                $stationdate = (new MeteoRepository())->fetchMeteoDataDate("$id_station","$date");
                $stationpardate["$i"] = [$stationdate,$date];
            }
            $stations = (new StationRepository())->selectAll();
            self::afficheVue('station/dashboard.php', ['stations' => $stations, 'stationpardate' => $stationpardate], 'Liste des stations'); // Redirection vers la vue
        }

    }

    public static function choix(){
        $stations = (new StationRepository())->selectAll();
        self::afficheVue('station/choix.php', ['stations' => $stations], "Choix d'un intervalle temporel");
    }

    public static function choixx(){
        $stations = (new StationRepository())->selectAll();
        self::afficheVue('station/choixx.php', ['stations' => $stations], "Choix d'un lieu");
    }

    public static function test(){
        $queryString = $_GET['de'];
        parse_str($queryString, $queryArray);
        $temporalite = $queryArray['type'];
        $typee = $_GET['type'];
        switch ($typee) {
            case 'station':
                $valeur_lieu = "%27" . $_GET['valeur1'] . "%27";
                break;
            case 'departement':
                $valeur_lieu = "%27" . $_GET['valeur2'] . "%27";
                break;
            case 'region':
                $valeur_lieu = "%27" . $_GET['valeur3'] . "%27";
                break;
            default:
                $valeur_lieu = 'null';
        }

 
        switch ($temporalite) {
            case 'jour':
                $valeur_date = $_GET['jour-choisi'] ?? 'null';
                break;
            case 'semaine':
                $valeur_date = $_GET['semaine-choisi'] ?? 'null';
                break;
            case "mois":
                $valeur_date = $_GET['mois-choisi'] ?? '2017-07';
                break;
            case "saison":
                $valeur_date = $_GET['saison-choisi'] ?? 'null';
                break;
            default:
                $valeur_date = 'null';
        }
        
        

        $annee = $_GET['annee-type'];
        $valeurs= (new MeteoRepository())->fetchMeteoDataSemaine($annee,$temporalite,$typee,$valeur_lieu,$valeur_date);
        $stations = (new StationRepository())->selectAll();
        self::afficheVue('station/test.php', ['valeurs' => $valeurs], 'Dashboard');
    }
/*
    public static function semaine(){
        if (isset($_GET["semaine"]) && isset($_GET["annee"])){
            $NumSemaineChoisi = $_GET["semaine"];
            $AnneeChoisi = $_GET["annee"];
            $dateChoisi = new \DateTime();
            $dateChoisi->setISODate($AnneeChoisi, $NumSemaineChoisi);
            for ($i=0; $i < 7; $i++) {
                $jour = clone $dateChoisi;
                $jour->modify("+$i day");
                $DataJour = (new MeteoRepository())->fetchMeteoDataDate("78897",$jour->format('Y-m-d'));
                $DataMeteoSemaine[$i] = $DataJour;
            }
            self::afficheVue('station/semaine.php', ['DataMeteoSemaine' => $DataMeteoSemaine], 'Liste des stations');
        } else {

        }
    }

*/
    
}


?>