<?php

namespace App\SAE3\model\Repository;

use App\SAE3\model\DataObject\Meteo;
use App\SAE3\model\DataObject\MeteoGranularite;

class MeteoRepository {

    public function fetchMeteoData($station_id) {

        $station_id = trim($station_id);
        
        $url = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?" . 
               http_build_query([
                   'select' => 'numer_sta,date,tend AS variation_pression_3h,cod_tend AS type_tendance_barometrique,' .
                              'dd AS direction_vent,ff AS vitesse_vent,tc AS temperature,u AS humidite,' .
                              'vv AS visibilite_horizontale,pres AS pression_station,ww AS temps_present',
                   'where' => "numer_sta = '$station_id'",
                   'order_by' => 'date DESC',
                   'limit' => 1
               ]);

        $options = [
            "http" => [
                "header" => "User-Agent: Mozilla/5.0",
                "timeout" => 60
            ]
        ];

        $context = stream_context_create($options);
        
        error_log("Tentative de récupération des données pour la station : " . $station_id);
        error_log("URL de l'API : " . $url);
        
        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            error_log("Erreur lors de la récupération des données météo pour la station " . $station_id);
            return null;
        }

        $data = json_decode($response, true);
        
        if (empty($data['results'])) {
            error_log("Aucune donnée trouvée pour la station " . $station_id);
            return null;
        }

        return new Meteo($data['results'][0]);
    }


    public function fetchMeteoDataDate($station_id,$date){
        $url = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?select=numer_sta%2Cdate%2Ctend%20AS%20variation_pression_3h%2CAVG(rr6)%20as%20precipitation%2Ccod_tend%20AS%20type_tendance_barometrique%2Cdd%20AS%20direction_vent%2Cff%20AS%20vitesse_vent%2Ctc%20AS%20temperature%2Cu%20AS%20humidite%2Cvv%20AS%20visibilite_horizontale%2Cn%20AS%20nebulosite_totale%2Ccl%20AS%20type_nuage_etage_inferieur%2Ccm%20AS%20type_nuage_etage_moyen%2Cch%20AS%20type_nuage_etage_superieur%2Chbas%20AS%20hauteur_base_nuages_inferieur%2Cpres%20AS%20pression_station%2Cniv_bar%20AS%20niveau_barometrique&where=numer_sta%3D%27$station_id%27%20AND%20date%3Ddate%27$date%27&order_by=date%20DESC&limit=1";
        
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
            return new MeteoGranularite($data['results'][0]);
        }

        return null;
    }

    public function fetchMeteoDataMeteotheque(string $localisation,string $dateDebut,string $dateFin,string $type_localisation){
        if ($type_localisation == "region"){
            $nomCond="nom_reg";
        } else {
            $nomCond="nom_dept";
        }
        $url = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?select=numer_sta%2C%20date%2C%20AVG(tend)%20AS%20variation_pression_3h%2C%20cod_tend%20AS%20type_tendance_barometrique%2C%20AVG(dd)%20AS%20direction_vent%2C%20AVG(ff)%20AS%20vitesse_vent%2C%20AVG(tc)%20AS%20temperature%2C%20AVG(u)%20AS%20humidite%2C%20AVG(vv)%20AS%20visibilite_horizontale%2C%20AVG(n)%20AS%20nebulosite_totale%2C%20cl%20AS%20type_nuage_etage_inferieur%2C%20cm%20AS%20type_nuage_etage_moyen%2C%20ch%20AS%20type_nuage_etage_superieur%2C%20AVG(hbas)%20AS%20hauteur_base_nuages_inferieur%2C%20AVG(pres)%20AS%20pression_station%2C%20niv_bar%20AS%20niveau_barometrique&where=date%20%3E%3D%20%22$dateDebut%22%20%20%20%20AND%20date%20%3C%3D%20%22$dateFin%22%20AND%20$nomCond%20%3D%20'$localisation'&group_by=date&o";

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

        if (isset($data['results']) && is_array($data['results'])) {
            $result = [];
            foreach ($data['results'] as $DonneDate) {
                $result[] = new Meteo($DonneDate);;
            }
            return $result;
        }

        return null;
    }

    
    /* La granularité */

    public function fetchMeteoDataSemaine(string $annee, string $temporalite, string $type,string $valeur_lieu,string $valeur_date){

        
        $type_defini = '';
        $date_defini = '';



        switch ($type) {
            case 'station':
                $type_defini = "numer_sta%3D$valeur_lieu%20AND";
                break;
            case 'departement':
                $type_defini = "nom_dept%3D$valeur_lieu%20AND";
                break; 
            case 'region':
                $type_defini = "nom_reg%3D$valeur_lieu%20AND";
                break; 
            case 'national':
                $type_defini = '';
                break;   
        }

        switch ($temporalite) {
            case 'jour':
                $startDate = new \DateTime($valeur_date);
                $startDateString = $startDate->format('Y-m-d');
                $endDateString = $startDateString;
                $url2 = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?select=AVG(tend)%20AS%20variation_pression_3h%2Cmax(tend)%20AS%20variation_pression_3hMax%2Cmin(tend)%20AS%20variation_pression_3hMin%2CAVG(ff)%20AS%20vitesse_vent%2Cmax(ff)%20AS%20vitesse_ventMax%2Cmin(ff)%20AS%20vitesse_ventMin%2CAVG(tc)%20AS%20temperature%2Cmax(tc)%20AS%20temperatureMax%2Cmin(tc)%20AS%20temperatureMin%2CAVG(u)%20AS%20humidite%2Cmax(u)%20AS%20humiditeMax%2Cmin(u)%20AS%20humiditeMin%2CAVG(vv)%20AS%20visibilite_horizontale%2Cmax(vv)%20AS%20visibilite_horizontaleMax%2Cmin(vv)%20AS%20visibilite_horizontaleMin%2CAVG(n)%20AS%20nebulosite_totale%2Cmax(n)%20AS%20nebulosite_totaleMax%2Cmin(n)%20AS%20nebulosite_totaleMin%2CAVG(pres)%20AS%20pression_station%2Cmax(pres)%20AS%20pression_stationMax%2Cmin(pres)%20AS%20pression_stationMin%2Cniv_bar%20AS%20niveau_barometrique%2Ccod_tend%20AS%20type_tendance_barometrique%2Cdate%2Cnumer_sta%2CAVG(rr6)%20as%20precipitation%2Cmax(rr6)%20as%20precipitationMax%2Cmin(rr6)%20as%20precipitationMin&where=$type_defini%20date%20IN%20[%27$startDateString%27..%27$endDateString%27]";
            case 'semaine':
                $startDate = new \DateTime($valeur_date);
                $endDate = $startDate;
                $endDate->modify('+6 days');
                $startDateString = $startDate->format('Y-m-d');
                $endDateString = $endDate->format('Y-m-d');
                $url2 = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?select=AVG(tend)%20AS%20variation_pression_3h%2Cmax(tend)%20AS%20variation_pression_3hMax%2Cmin(tend)%20AS%20variation_pression_3hMin%2CAVG(ff)%20AS%20vitesse_vent%2Cmax(ff)%20AS%20vitesse_ventMax%2Cmin(ff)%20AS%20vitesse_ventMin%2CAVG(tc)%20AS%20temperature%2Cmax(tc)%20AS%20temperatureMax%2Cmin(tc)%20AS%20temperatureMin%2CAVG(u)%20AS%20humidite%2Cmax(u)%20AS%20humiditeMax%2Cmin(u)%20AS%20humiditeMin%2CAVG(vv)%20AS%20visibilite_horizontale%2Cmax(vv)%20AS%20visibilite_horizontaleMax%2Cmin(vv)%20AS%20visibilite_horizontaleMin%2CAVG(n)%20AS%20nebulosite_totale%2Cmax(n)%20AS%20nebulosite_totaleMax%2Cmin(n)%20AS%20nebulosite_totaleMin%2CAVG(pres)%20AS%20pression_station%2Cmax(pres)%20AS%20pression_stationMax%2Cmin(pres)%20AS%20pression_stationMin%2Cniv_bar%20AS%20niveau_barometrique%2Ccod_tend%20AS%20type_tendance_barometrique%2Cdate%2Cnumer_sta%2CAVG(rr6)%20as%20precipitation%2Cmax(rr6)%20as%20precipitationMax%2Cmin(rr6)%20as%20precipitationMin&where=$type_defini%20date%20IN%20[%27$startDateString%27..%27$endDateString%27]";
                
                break;

            case 'mois':
                $startDate = new \DateTime($valeur_date . '-01');
                $endDate = new \DateTime($valeur_date . '-01');
                $endDate->modify('last day of this month');
                $startDateString = $startDate->format('Y-m-d');
                $endDateString = $endDate->format('Y-m-d');
                $url2 = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?select=AVG(tend)%20AS%20variation_pression_3h%2Cmax(tend)%20AS%20variation_pression_3hMax%2Cmin(tend)%20AS%20variation_pression_3hMin%2CAVG(ff)%20AS%20vitesse_vent%2Cmax(ff)%20AS%20vitesse_ventMax%2Cmin(ff)%20AS%20vitesse_ventMin%2CAVG(tc)%20AS%20temperature%2Cmax(tc)%20AS%20temperatureMax%2Cmin(tc)%20AS%20temperatureMin%2CAVG(u)%20AS%20humidite%2Cmax(u)%20AS%20humiditeMax%2Cmin(u)%20AS%20humiditeMin%2CAVG(vv)%20AS%20visibilite_horizontale%2Cmax(vv)%20AS%20visibilite_horizontaleMax%2Cmin(vv)%20AS%20visibilite_horizontaleMin%2CAVG(n)%20AS%20nebulosite_totale%2Cmax(n)%20AS%20nebulosite_totaleMax%2Cmin(n)%20AS%20nebulosite_totaleMin%2CAVG(pres)%20AS%20pression_station%2Cmax(pres)%20AS%20pression_stationMax%2Cmin(pres)%20AS%20pression_stationMin%2Cniv_bar%20AS%20niveau_barometrique%2Ccod_tend%20AS%20type_tendance_barometrique%2Cdate%2Cnumer_sta%2CAVG(rr6)%20as%20precipitation%2Cmax(rr6)%20as%20precipitationMax%2Cmin(rr6)%20as%20precipitationMin&where=$type_defini%20date%20IN%20[%27$startDateString%27..%27$endDateString%27]";
                break; 
            case 'saison':
                $year = substr($valeur_date, 0, 4);

                // Extraire la saison (après le "-")
                $seasonName = substr($valeur_date, 5);
                switch ($seasonName) {
                    case 'printemps':
                        $startDate = new \DateTime("$year-03-20");
                        $endDate = new \DateTime("$year-06-21");
                    case 'ete':
                        $startDate = new \DateTime("$year-06-21");
                        $endDate = new \DateTime("$year-09-22");
                    case 'automne':
                        $startDate = new \DateTime("$year-09-22");
                        $endDate = new \DateTime("$year-12-21");
                    case 'hiver':
                        $startDate = new \DateTime("$year-12-21");
                        $endDate = new \DateTime("$year-03-20");
                }
                $startDateString = $startDate->format('Y-m-d');
                $endDateString = $endDate->format('Y-m-d');

                $url2 = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?select=AVG(tend)%20AS%20variation_pression_3h%2Cmax(tend)%20AS%20variation_pression_3hMax%2Cmin(tend)%20AS%20variation_pression_3hMin%2CAVG(ff)%20AS%20vitesse_vent%2Cmax(ff)%20AS%20vitesse_ventMax%2Cmin(ff)%20AS%20vitesse_ventMin%2CAVG(tc)%20AS%20temperature%2Cmax(tc)%20AS%20temperatureMax%2Cmin(tc)%20AS%20temperatureMin%2CAVG(u)%20AS%20humidite%2Cmax(u)%20AS%20humiditeMax%2Cmin(u)%20AS%20humiditeMin%2CAVG(vv)%20AS%20visibilite_horizontale%2Cmax(vv)%20AS%20visibilite_horizontaleMax%2Cmin(vv)%20AS%20visibilite_horizontaleMin%2CAVG(n)%20AS%20nebulosite_totale%2Cmax(n)%20AS%20nebulosite_totaleMax%2Cmin(n)%20AS%20nebulosite_totaleMin%2CAVG(pres)%20AS%20pression_station%2Cmax(pres)%20AS%20pression_stationMax%2Cmin(pres)%20AS%20pression_stationMin%2Cniv_bar%20AS%20niveau_barometrique%2Ccod_tend%20AS%20type_tendance_barometrique%2Cdate%2Cnumer_sta%2CAVG(rr6)%20as%20precipitation%2Cmax(rr6)%20as%20precipitationMax%2Cmin(rr6)%20as%20precipitationMin&where=$type_defini%20date%20IN%20[%27$startDateString%27..%27$endDateString%27]";
                break; 

            case 'annee':
                $startDate = new \DateTime("$annee-01-01");
                $endDate = new \DateTime("$annee-12-31");
                $startDateString = $startDate->format('Y-m-d');
                $endDateString = $endDate->format('Y-m-d');
                $url2 = "https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/donnees-synop-essentielles-omm/records?select=AVG(tend)%20AS%20variation_pression_3h%2Cmax(tend)%20AS%20variation_pression_3hMax%2Cmin(tend)%20AS%20variation_pression_3hMin%2CAVG(ff)%20AS%20vitesse_vent%2Cmax(ff)%20AS%20vitesse_ventMax%2Cmin(ff)%20AS%20vitesse_ventMin%2CAVG(tc)%20AS%20temperature%2Cmax(tc)%20AS%20temperatureMax%2Cmin(tc)%20AS%20temperatureMin%2CAVG(u)%20AS%20humidite%2Cmax(u)%20AS%20humiditeMax%2Cmin(u)%20AS%20humiditeMin%2CAVG(vv)%20AS%20visibilite_horizontale%2Cmax(vv)%20AS%20visibilite_horizontaleMax%2Cmin(vv)%20AS%20visibilite_horizontaleMin%2CAVG(n)%20AS%20nebulosite_totale%2Cmax(n)%20AS%20nebulosite_totaleMax%2Cmin(n)%20AS%20nebulosite_totaleMin%2CAVG(pres)%20AS%20pression_station%2Cmax(pres)%20AS%20pression_stationMax%2Cmin(pres)%20AS%20pression_stationMin%2Cniv_bar%20AS%20niveau_barometrique%2Ccod_tend%20AS%20type_tendance_barometrique%2Cdate%2Cnumer_sta%2CAVG(rr6)%20as%20precipitation%2Cmax(rr6)%20as%20precipitationMax%2Cmin(rr6)%20as%20precipitationMin&where=$type_defini%20date%20IN%20[%27$startDateString%27..%27$endDateString%27]";
                break;   
        }


        $options = [
            "http" => [
                "header" => "User-Agent: PHP",
                "timeout" => 30
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($url2, false, $context);

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
            
            return new MeteoGranularite($data['results'][0]);
        }

        return null;
    }

}
