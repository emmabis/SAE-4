<?php

namespace App\SAE3\model\DataObject;

class MeteoGranularite {
    private ?float $temperature;
    private ?float $temperatureMax;
    private ?float $temperatureMin;
    private ?float $temperatureEcartype;

    private ?float $humidite;
    private ?float $humiditeMax;
    private ?float $humiditeMin;
    private ?float $humiditeEcartype;

    private ?float $pression;
    private ?float $pressionMax;
    private ?float $pressionMin;
    private ?float $pressionEcartype;
    
    private ?float $variationpression3h;
    private ?float $variationpression3hMax;
    private ?float $variationpression3hMin;
    private ?float $variationpression3hEcartype;

    private ?float $vitesseVent;
    private ?float $vitesseVentMax;
    private ?float $vitesseVentMin;
    private ?float $vitesseVentEcartype;

    private ?float $directionVent;
    private ?string $typeNuage;

    private $precipation;
    private $precipationMax;
    private $precipationMin;
    private $precipationEcartype;

    private $nebulositetotal;
    private $nebulositetotalMax;
    private $nebulositetotalMin;
    private $nebulositetotalEcartype;

    private $hauteurbasnuage;

    private ?float $visibilitehorizontale;
    private ?float $visibilitehorizontaleMax;
    private ?float $visibilitehorizontaleMin;
    private ?float $visibilitehorizontaleEcartype;

    private ?float $pressionstation;
    private ?float $pressionstationMax;
    private ?float $pressionstationMin;
    private ?float $pressionstationEcartype;

    private ?string $tempsPresent;
    private ?string $date;

    public function __construct($data) {
        if ($data === null) {
            $this->setTemperature(null);
            $this->setTemperatureMax(null);
            $this->setTemperatureMin(null);
            $this->setTemperatureEcartype(null);
            $this->setHumidite(null);
            $this->setHumiditeMax(null);
            $this->setHumiditeMin(null);
            $this->setHumiditeEcartype(null);
            $this->setPression(null);
            $this->setPressionMax(null);
            $this->setPressionMin(null);
            $this->setPressionEcartype(null);
            $this->setVariationpression3h(null);
            $this->setVariationpression3hMax(null);
            $this->setVariationpression3hMin(null);
            $this->setVariationpression3hEcartype(null);
            $this->setVitesseVent(null);
            $this->setVitesseVentMax(null);
            $this->setVitesseVentMin(null);
            $this->setVitesseVentEcartype(null);
            $this->setDirectionVent(null);
            $this->setTypeNuage('Aucun nuage détecté');
            $this->setNebulositeTotal('Pas de nebolisté');
            $this->setNebulositeTotalMax('Pas de nebolisté');
            $this->setNebulositeTotalMin('Pas de nebolisté');
            $this->setNebulositeTotalEcartype('Pas de nebolisté');
            $this->setHauteurBasNuage('Pas de nuage');
            $this->setVisibiliteHorizontale(null);
            $this->setVisibiliteHorizontaleMax(null);
            $this->setVisibiliteHorizontaleMin(null);
            $this->setVisibiliteHorizontaleEcartype(null);
            $this->setPressionStation(null);
            $this->setPressionStationMax(null);
            $this->setPressionStationMin(null);
            $this->setPressionStationEcartype(null);
            $this->setPrecipation(null);
            $this->setPrecipationMax(null);
            $this->setPrecipationMin(null);
            $this->setPrecipationEcartype(null);
            $this->setTempsPresent(null);
            $this->setDate(null);
            return;
        }

        $this->setTemperature($data['temperature'] ?? null);
        $this->setTemperatureMax($data['temperatureMax'] ?? null);
        $this->setTemperatureMin($data['temperatureMin'] ?? null);
        $this->setTemperatureEcartype($data['temperatureEcartype'] ?? null);

        $this->setHumidite($data['humidite'] ?? 1);
        $this->setHumiditeMax($data['humiditeMax'] ?? 1);
        $this->setHumiditeMin($data['humiditeMin'] ?? 1);
        $this->setHumiditeEcartype($data['humiditeEcartype'] ?? 1);

        $this->setPression($data['pression_station'] ?? null);
        $this->setPressionMax($data['pression_stationMax'] ?? null);
        $this->setPressionMin($data['pression_stationMin'] ?? null);
        $this->setPressionEcartype($data['pression_stationEcartype'] ?? null);

        $this->setVitesseVent($data['vitesse_vent'] ?? null);
        $this->setVitesseVentMax($data['vitesse_ventMax'] ?? null);
        $this->setVitesseVentMin($data['vitesse_ventMin'] ?? null);
        $this->setVitesseVentEcartype($data['vitesse_ventEcartype'] ?? null);

        $this->setDirectionVent($data['direction_vent'] ?? null);
        $this->setTypeNuage($data['type_nuage_etage_inferieur'] ?? 'Aucun nuage détecté');

        $this->setNebulositeTotal($data['nebulosite_totale'] ?? 'Pas de nebolisté');
        $this->setNebulositeTotalMax($data['nebulosite_totaleMax'] ?? 'Pas de nebolisté');
        $this->setNebulositeTotalMin($data['nebulosite_totaleMin'] ?? 'Pas de nebolisté');
        $this->setNebulositeTotalEcartype($data['nebulosite_totaleEcartype'] ?? 'Pas de nebolisté');

        $this->setHauteurBasNuage($data['hauteur_base_nuages_inferieur'] ?? 'Pas de nuage');

        $this->setVisibiliteHorizontale($data['visibilite_horizontale'] ?? null);
        $this->setVisibiliteHorizontaleMax($data['visibilite_horizontaleMax'] ?? null);
        $this->setVisibiliteHorizontaleMin($data['visibilite_horizontaleMin'] ?? null);
        $this->setVisibiliteHorizontaleEcartype($data['visibilite_horizontaleEcartype'] ?? null);

        $this->setPressionStation($data['pression_station'] ?? null);
        $this->setPressionStationMax($data['pression_stationMax'] ?? null);
        $this->setPressionStationMin($data['pression_stationMin'] ?? null);
        $this->setPressionStationEcartype($data['pression_stationEcartype'] ?? null);
        
        $this->setPrecipation($data['precipitation'] ?? null);
        $this->setPrecipationMax($data['precipitationMax'] ?? null);
        $this->setPrecipationMin($data['precipitationMin'] ?? null);
        $this->setPrecipationEcartype($data['precipitationEcartype'] ?? null);

        $this->setVariationpression3h($data['variation_pression_3h'] ?? null);
        $this->setVariationpression3hMax($data['variation_pression_3hMax'] ?? null);
        $this->setVariationpression3hMin($data['variation_pression_3hMin'] ?? null);
        $this->setVariationpression3hEcartype($data['variation_pression_3hEcartype'] ?? null);

        $this->setTempsPresent($data['temps_present'] ?? null);
        $this->setDate($data['date'] ?? null);
    }
    
    public function getTemperature() { return $this->temperature; }
    public function setTemperature($temperature) { $this->temperature = $temperature; }
    
    public function getTemperatureMax() { return $this->temperatureMax; }
    public function setTemperatureMax($temperatureMax) { $this->temperatureMax = $temperatureMax; }
    
    public function getTemperatureMin() { return $this->temperatureMin; }
    public function setTemperatureMin($temperatureMin) { $this->temperatureMin = $temperatureMin; }
    
    public function getTemperatureEcartype() { return $this->temperatureEcartype; }
    public function setTemperatureEcartype($temperatureEcartype) { $this->temperatureEcartype = $temperatureEcartype; }
    
    public function getPrecipation() { return $this->precipation; }
    public function setPrecipation($precipation) { $this->precipation = $precipation; }

    public function getPrecipationMax() { return $this->precipationMax; }
    public function setPrecipationMax($precipationMax) { $this->precipationMax = $precipationMax; }

    public function getPrecipationMin() { return $this->precipationMin; }
    public function setPrecipationMin($precipationMin) { $this->precipationMin = $precipationMin; }

    public function getPrecipationEcartype() { return $this->precipationEcartype; }
    public function setPrecipationEcartype($precipationEcartype) { $this->precipationEcartype = $precipationEcartype; }

    public function getHumidite() { return $this->humidite; }
    public function setHumidite($humidite) { $this->humidite = $humidite; }
    
    public function getHumiditeMax() { return $this->humiditeMax; }
    public function setHumiditeMax($humiditeMax) { $this->humiditeMax = $humiditeMax; }
    
    public function getHumiditeMin() { return $this->humiditeMin; }
    public function setHumiditeMin($humiditeMin) { $this->humiditeMin = $humiditeMin; }
    
    public function getHumiditeEcartype() { return $this->humiditeEcartype; }
    public function setHumiditeEcartype($humiditeEcartype) { $this->humiditeEcartype = $humiditeEcartype; }
    
    public function getPression() { return $this->pression; }
    public function setPression($pression) { $this->pression = $pression; }
    
    public function getPressionMax() { return $this->pressionMax; }
    public function setPressionMax($pressionMax) { $this->pressionMax = $pressionMax; }
    
    public function getPressionMin() { return $this->pressionMin; }
    public function setPressionMin($pressionMin) { $this->pressionMin = $pressionMin; }
    
    public function getPressionEcartype() { return $this->pressionEcartype; }
    public function setPressionEcartype($pressionEcartype) { $this->pressionEcartype = $pressionEcartype; }
    
    public function getVitesseVent() { return $this->vitesseVent; }
    public function setVitesseVent($vitesseVent) { $this->vitesseVent = $vitesseVent; }
    
    public function getVitesseVentMax() { return $this->vitesseVentMax; }
    public function setVitesseVentMax($vitesseVentMax) { $this->vitesseVentMax = $vitesseVentMax; }
    
    public function getVitesseVentMin() { return $this->vitesseVentMin; }
    public function setVitesseVentMin($vitesseVentMin) { $this->vitesseVentMin = $vitesseVentMin; }
    
    public function getVitesseVentEcartype() { return $this->vitesseVentEcartype; }
    public function setVitesseVentEcartype($vitesseVentEcartype) { $this->vitesseVentEcartype = $vitesseVentEcartype; }
    
    public function getDirectionVent() { return $this->directionVent; }
    public function setDirectionVent($directionVent) { $this->directionVent = $directionVent; }
    
    public function getTypeNuage() { return $this->typeNuage; }
    public function setTypeNuage($typeNuage) { $this->typeNuage = $typeNuage; }

    
    public function getNebulositeTotal() { return $this->nebulositetotal; }
    public function setNebulositeTotal($nebulositetotal) { $this->nebulositetotal = $nebulositetotal; }
    
    public function getNebulositeTotalMax() { return $this->nebulositetotalMax; }
    public function setNebulositeTotalMax($nebulositetotalMax) { $this->nebulositetotalMax = $nebulositetotalMax; }
    
    public function getNebulositeTotalMin() { return $this->nebulositetotalMin; }
    public function setNebulositeTotalMin($nebulositetotalMin) { $this->nebulositetotalMin = $nebulositetotalMin; }
    
    public function getNebulositeTotalEcartype() { return $this->nebulositetotalEcartype; }
    public function setNebulositeTotalEcartype($nebulositetotalEcartype) { $this->nebulositetotalEcartype = $nebulositetotalEcartype; }
    
    public function getTempsPresent() { return $this->tempsPresent; }
    public function setTempsPresent($tempsPresent) { $this->tempsPresent = $tempsPresent; }

    public function getHauteurBasNuage() { return $this->hauteurbasnuage; }
    public function setHauteurBasNuage($hauteurbasnuage) { $this->hauteurbasnuage = $hauteurbasnuage; }
    
    public function getVisibiliteHorizontale() { return $this->visibilitehorizontale; }
    public function setVisibiliteHorizontale($visibilitehorizontale) { $this->visibilitehorizontale = $visibilitehorizontale; }
    
    public function getVisibiliteHorizontaleMax() { return $this->visibilitehorizontaleMax; }
    public function setVisibiliteHorizontaleMax($visibilitehorizontaleMax) { $this->visibilitehorizontaleMax = $visibilitehorizontaleMax; }
    
    public function getVisibiliteHorizontaleMin() { return $this->visibilitehorizontaleMin; }
    public function setVisibiliteHorizontaleMin($visibilitehorizontaleMin) { $this->visibilitehorizontaleMin = $visibilitehorizontaleMin; }
    
    public function getVisibiliteHorizontaleEcartype() { return $this->visibilitehorizontaleEcartype; }
    public function setVisibiliteHorizontaleEcartype($visibilitehorizontaleEcartype) { $this->visibilitehorizontaleEcartype = $visibilitehorizontaleEcartype; }

    public function getPressionStation() { return $this->pressionstation; }
    public function setPressionStation($pressionstation) { $this->pressionstation = $pressionstation; }

    public function getPressionStationMax() { return $this->pressionstationMax; }
    public function setPressionStationMax($pressionstationMax) { $this->pressionstationMax = $pressionstationMax; }

    public function getPressionStationMin() { return $this->pressionstationMin; }
    public function setPressionStationMin($pressionstationMin) { $this->pressionstationMin = $pressionstationMin; }

    public function getPressionStationEcartype() { return $this->pressionstationEcartype; }
    public function setPressionStationEcartype($pressionstationEcartype) { $this->pressionstationEcartype = $pressionstationEcartype; }

    public function setVariationpression3h($data) { $this->variationpression3h = $data;}
    
    public function getVariationpression3h() {return $this->variationpression3h; }
    
    public function setVariationpression3hMax($data) {$this->variationpression3hMax = $data;}
    
    public function getVariationpression3hMax() {return $this->variationpression3hMax;}
    
    public function setVariationpression3hMin($data) {$this->variationpression3hMin = $data;}
    
    public function getVariationpression3hMin() {return $this->variationpression3hMin;}
    
    public function setVariationpression3hEcartype($data) {$this->variationpression3hEcartype = $data;}
    
    public function getVariationpression3hEcartype() {return $this->variationpression3hEcartype;}

    public function getDate() { return $this->date; }
    public function setDate($date) { $this->date = $date; }

    private function convertirTempsPresent($code) {
        if ($code === null) return "Données non disponibles";
        
        $code = str_pad($code, 2, '0', STR_PAD_LEFT);
        
        $fichier = dirname(__DIR__, 3) . '/txt/phrase_temps';
        
        try {
            $lignes = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lignes === false) {
                return "Erreur : impossible de lire le fichier";
            }
            
            foreach ($lignes as $ligne) {
                if (strpos($ligne, $code) === 0) {
                    return trim(substr($ligne, 5));
                }
            }
            
            return "Code temps inconnu ($code)";
            
        } catch (\Exception $e) {
            return "Erreur lors de la lecture des descriptions météo";
        }
    }

    private function convertirTendanceBarometrique($code) {
        if ($code === null) return "Données non disponibles";
        
        $fichier = dirname(__DIR__, 3) . '/txt/phrase_barometrique';
        
        try {
            $lignes = file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lignes === false) {
                return "Erreur : impossible de lire le fichier";
            }
            
            foreach ($lignes as $ligne) {
                if (strpos($ligne, $code) === 0) {
                    return trim(substr($ligne, 4));
                }
            }
            
            return "Code barométrique inconnu ($code)";
            
        } catch (\Exception $e) {
            return "Erreur lors de la lecture des descriptions barométriques";
        }
    }
}