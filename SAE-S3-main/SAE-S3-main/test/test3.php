<?php

namespace App\SAE3\model\Repository;

use App\SAE3\model\DataObject\Meteo;

class MeteoRepository {

    public function fetchMeteoData($station_id) {
        $url = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?select=numer_sta%2Cdate%2Ctend%20AS%20variation_pression_3h%2Ccod_tend%20AS%20type_tendance_barometrique%2Cdd%20AS%20direction_vent%2Cff%20AS%20vitesse_vent%2Ct%20AS%20temperature%2Cu%20AS%20humidite%2Cvv%20AS%20visibilite_horizontale%2Cn%20AS%20nebulosite_totale%2Ccl%20AS%20type_nuage_etage_inferieur%2Ccm%20AS%20type_nuage_etage_moyen%2Cch%20AS%20type_nuage_etage_superieur%2Chbas%20AS%20hauteur_base_nuages_inferieur%2Cpres%20AS%20pression_station%2Cniv_bar%20AS%20niveau_barometrique&where=numer_sta%20%3D%20$station_id&order_by=date%20DESC&limit=1";

        $options = [
            "http" => [
                "header" => "User-Agent: PHP",
                "timeout" => 30
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $error = error_get_last();
            echo 'Erreur lors de la récupération des données météo : ' . $error['message'];
            return null;
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "<p>Erreur lors du décodage des données JSON : " . json_last_error_msg() . "</p>";
            return null;
        }

        if (isset($data['results'][0])) {
            return new Meteo($data['results'][0]);
        }

        return null;
    }

    public function fetchMeteoDataDate($station_id,$date){
        $url = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?select=numer_sta%2Cdate%2Ctend%20AS%20variation_pression_3h%2Ccod_tend%20AS%20type_tendance_barometrique%2Cdd%20AS%20direction_vent%2Cff%20AS%20vitesse_vent%2Ct%20AS%20temperature%2Cu%20AS%20humidite%2Cvv%20AS%20visibilite_horizontale%2Cn%20AS%20nebulosite_totale%2Ccl%20AS%20type_nuage_etage_inferieur%2Ccm%20AS%20type_nuage_etage_moyen%2Cch%20AS%20type_nuage_etage_superieur%2Chbas%20AS%20hauteur_base_nuages_inferieur%2Cpres%20AS%20pression_station%2Cniv_bar%20AS%20niveau_barometrique&where=numer_sta%3D$station_id%20AND%20date_format(date%2C%27yyyy-MM-dd%27)%3D%27$date%27&order_by=date%20DESC&limit=1";

        $options = [
            "http" => [
                "header" => "User-Agent: PHP",
                "timeout" => 30
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $error = error_get_last();
            echo 'Erreur lors de la récupération des données météo : ' . $error['message'];
            return null;
        }
        
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "<p>Erreur lors du décodage des données JSON : " . json_last_error_msg() . "</p>";
            return null;
        }

        if (isset($data['results'][0])) {
            return new Meteo($data['results'][0]);
        }

        return null;
    }

}



namespace App\SAE3\Controller;

use App\SAE3\model\Repository\StationRepository;
use App\SAE3\model\Repository\MeteoRepository;

class ControllerStation {

    public static function readAll() {
        $stationpardate = [];
        for ($i=1; $i < 8; $i++) { 
            $date = (new \DateTime())->modify("-$i day")->format('Y-m-d');
            $stationdate = (new MeteoRepository())->fetchMeteoDataDate("78897","$date");
            $stationpardate["$i"] = [$stationdate,$date];
        }
        $stations = (new StationRepository())->selectAll();
        self::afficheVue('station/list.php', ['stations' => $stations, 'stationpardate' => $stationpardate], 'Liste des stations'); // Redirection vers la vue
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

    public static function error(string $errorMessage){
        self::afficheVue('station/error.php', ['error' => $errorMessage], 'Erreur');
    }

    private static function afficheVue(string $cheminVueBody, array $parametres = [], string $pagetitle = ''): void {
        $parametres['cheminVueBody'] = $cheminVueBody;
        $parametres['pagetitle'] = $pagetitle;
    
        extract($parametres);
        require __DIR__ . "/../view/view.php";
    }
    
}


?>




------------------------------------------------------------

<?php

namespace App\SAE3\Controller;

use App\SAE3\model\Repository\StationRepository;
use App\SAE3\model\Repository\MeteoRepository;
use App\SAE3\Controller\GenericController;
/*
class ControllerStation extends GenericController {

    public static function readAll() {
        $stationpardate = [];
        for ($i=1; $i < 8; $i++) { 
            $date = (new \DateTime())->modify("-$i day")->format('Y-m-d');
            $stationdate = (new MeteoRepository())->fetchMeteoDataDate("78897","$date");
            $stationpardate["$i"] = $stationdate;
        }
        $stations = (new StationRepository())->selectAll();
        self::afficheVue('station/list.php', ['stations' => $stations, 'stationpardate' => $stationpardate], 'Liste des stations'); // Redirection vers la vue
    }

    public static function regionCarte(){
        $region = $_GET['region'] ?? null;

        if ($region) {
            $departements = (new StationRepository())->selectAllWhere("nom_reg",$region); 
            self::afficheVue('station/infoRegion.php', ['departements' => $departements], 'Liste des stations'); 
        } else {
            self::rediriger("../web/frontController.php?action=regionCarte&controller=station", "warning", "La région n'existe pas.");
        }
    }

    public static function read() {
        $numer_sta = $_GET['numer_sta'] ?? null;
    
        if ($numer_sta) {
            $station = (new StationRepository())->select($numer_sta);
            if (is_object($station)) {
                self::afficheVue('station/detail.php', ['station' => $station], 'Détail station');
            } else {
                self::rediriger("../web/frontController.php?action=read&controller=station&numer_sta=$numer_sta", "warning", "Il n'y a pas de donnée !");
            }
        } else {
            self::rediriger("../web/frontController.php?action=readAll&controller=station", "warning", "Veuillez mettre une station.");
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
            self::rediriger("../web/frontController.php?action=rechercheStation&controller=station", "warning", "Il n'y a pas de donnée !");
        }
    }

}


?>