<?php

namespace App\SAE3\model\DataObject;

class Meteo {
    private ?float $temperature;
    private ?float $humidite;
    private ?float $pression;
    private ?float $vitesseVent;
    private ?float $directionVent;
    private ?string $typeNuage;

    private $nebulositetotal;
    private $hauteurbasnuage;
    private ?float $visibilitehorizontale;
    private ?float $pressionstation;
    private ?float $variationpressionen3h;
    private ?string $typetendancebarometrique;
    private ?string $tempsPresent;
    private ?string $date;

    public function __construct($data) {
        $this->setTemperature($data['temperature']);
        $this->setHumidite($data['humidite'] ?? 1);
        $this->setPression($data['pression_station'] ?? null);
        $this->setVitesseVent($data['vitesse_vent'] ?? null);
        $this->setDirectionVent($data['direction_vent'] ?? null);
        $this->setTypeNuage($data['type_nuage_etage_inferieur'] ?? 'Aucun nuage détecté');
        $this->setnebulositetotal($data['nebulosite_totale'] ?? 'Pas de nebolisté');
        $this->sethauteurbasnuage($data['hauteur_base_nuages_inferieur'] ?? 'Pas de nuage');
        $this->setvisibilitehorizontale($data['visibilite_horizontale'] );
        $this->setpressionstation($data['pression_station']);
        $this->setvariationpression3h($data['variation_pression_3h'] );
        $this->settypetendancebarometrique($data['type_tendance_barometrique']);
        $this->setTempsPresent($data['temps_present'] ?? null);
        $this->setDate($data['date'] ?? null);
    }
    public function getDate(){
        return $this->date;
    }
    public function setDate($date){
        $this->date=$date;
    }

    public function getTemperature() {
        return $this->temperature;
    }

    public function setTemperature($temperature) {
        $this->temperature = $temperature;
    }

    public function getHumidite() {
        return $this->humidite ?? 1;
    }

    public function setHumidite($humidite) {
        $this->humidite = $humidite ?? 1;
     }

    public function getPression() {
        return $this->pression;
    }

    public function setPression($pression) {
        $this->pression = $pression;
    }

    public function getVitesseVent() {
        if ($this->vitesseVent == NULL) {
            return 0;
        }
        return $this->vitesseVent;
    }

    public function setVitesseVent($vitesseVent) {
        $this->vitesseVent = $vitesseVent;
    }

    public function getDirectionVent() {
        return $this->directionVent;
    }

    public function setDirectionVent($directionVent) {
        $this->directionVent = $directionVent;
    }

    public function getTypeNuage() {
        return $this->typeNuage;
    }

    public function setTypeNuage($typeNuage) {
        $this->typeNuage = $typeNuage;
    }


    public function getnebulositetotal(){
        return $this->nebulositetotal;
    }
    public function setnebulositetotal($nebulositetotal){
        $this->nebulositetotal = $nebulositetotal;
    }

    public function gethauteurbasenuage(){
        return $this->hauteurbasnuage;
    }
    public function sethauteurbasnuage($hauteurbasnuage){
        $this->hauteurbasnuage= $hauteurbasnuage;
    }
    public function getvisibilitehorizontale(){
        return $this->visibilitehorizontale;
    }
    public function setvisibilitehorizontale($visibilitehorizontale){
        $this->visibilitehorizontale=$visibilitehorizontale;
    }
    public function getpressionstation(){
        return  $this->pressionstation;
    }
    public function setpressionstation($pressionstation){
        $this->pressionstation=$pressionstation;
    }
    public function getvariationpressionen3h(){
        return $this->variationpressionen3h;
    }
    public function setvariationpression3h($variationpressionen3h){
        $this->variationpressionen3h=$variationpressionen3h;
    }
    public function getTypeTendanceBarometrique() {
        return $this->convertirTendanceBarometrique($this->typetendancebarometrique);
    }

    public function settypetendancebarometrique($typetendancebarometrique){
        $this->typetendancebarometrique=$typetendancebarometrique;
    }


    public function getTempsPresent() {
        return $this->convertirTempsPresent($this->tempsPresent);
    }

    public function setTempsPresent($tempsPresent) {
        $this->tempsPresent = $tempsPresent;
    }

    private function convertirTempsPresent($code) {
        if ($code === null) return "Données non disponibles";
        
        // Formatage du code sur 2 chiffres
        $code = str_pad($code, 2, '0', STR_PAD_LEFT);
        
        // Chemin vers le fichier phrase_temps
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
        
        // Chemin vers le fichier phrase_barometrique
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
