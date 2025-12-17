<?php
namespace App\SAE3\model\DataObject;

class Station extends AbstractDataObject {
    private string $id_station;
    private string $nom;
    private float $latitude;
    private float $longitude;
    private int $altitude;
    private ?string $code_geo;
    private ?string $lib_geo;
    private ?string $code_dept;
    private ?string $nom_dept;
    private ?string $code_reg;
    private ?string $nom_reg;
    private ?string $code_epci;
    private ?string $nom_epci;

    public function __construct(
        string $id_station,
        string $nom,
        float $latitude,
        float $longitude,
        int $altitude,
        ?string $code_geo,
        ?string $lib_geo,
        ?string $code_dept,
        ?string $nom_dept,
        ?string $code_reg,
        ?string $nom_reg,
        ?string $code_epci,
        ?string $nom_epci
    ) {
        $this->id_station = $id_station;
        $this->nom = $nom;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->altitude = $altitude;
        $this->code_geo = $code_geo;
        $this->lib_geo = $lib_geo;
        $this->code_dept = $code_dept;
        $this->nom_dept = $nom_dept;
        $this->code_reg = $code_reg;
        $this->nom_reg = $nom_reg;
        $this->code_epci = $code_epci;
        $this->nom_epci = $nom_epci;
    }

    

    public function getId_station(): string {
        return $this->id_station;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function getLatitude(): float {
        return $this->latitude;
    }

    public function getLongitude(): float {
        return $this->longitude;
    }

    public function getAltitude(): int {
        return $this->altitude;
    }

    public function getCode_geo(): string {
        return $this->code_geo;
    }

    public function getLib_geo(): string {
        return $this->lib_geo;
    }

    public function getCode_dept(): string {
        if ($this->code_dept == NULL) {
            return 'il y a rien';
        }else {
            return $this->code_dept;   
        }
    }

    public function getNom_dept(): string {
        if ($this->nom_dept == NULL) {
            return 'il y a rien';
        }else {
            return $this->nom_dept;   
        }
    }

    public function getCode_reg(): string {
        if ($this->code_reg == NULL) {
            return 'il y a rien';
        }else {
            return $this->code_reg;   
        }
    }

    public function getNom_reg(): string {
        
        if ($this->nom_reg == NULL) {
            return 'il y a rien';
        }else {
            return $this->nom_reg;   
        }
    }

    public function getCode_epci(): string {
        return $this->code_epci;
    }

    public function getNom_epci(): string {
        return $this->nom_epci;
    }

    public function setId_station(string $id_station): void {
        $this->id_station = $id_station;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function setLatitude(float $latitude): void {
        $this->latitude = $latitude;
    }

    public function setLongitude(float $longitude): void {
        $this->longitude = $longitude;
    }

    public function setAltitude(int $altitude): void {
        $this->altitude = $altitude;
    }

    public function setCode_geo(string $code_geo): void {
        $this->code_geo = $code_geo;
    }

    public function setLib_geo(string $lib_geo): void {
        $this->lib_geo = $lib_geo;
    }

    public function setCode_dept(string $code_dept): void {
        $this->code_dept = $code_dept;
    }

    public function setNom_dept(string $nom_dept): void {
        $this->nom_dept = $nom_dept;
    }

    public function setCode_reg(string $code_reg): void {
        $this->code_reg = $code_reg;
    }

    public function setNom_reg(string $nom_reg): void {
        $this->nom_reg = $nom_reg;
    }

    public function setCode_epci(string $code_epci): void {
        $this->code_epci = $code_epci;
    }

    public function setNom_epci(string $nom_epci): void {
        $this->nom_epci = $nom_epci;
    }

    public function formatTableau(): array {
        return [
            "id_station" => $this->getId_station(),
            "nom" => $this->getNom(),
            "latitude" => $this->getLatitude(),
            "longitude" => $this->getLongitude(),
            "altitude" => $this->getAltitude(),
            "code_geo" => $this->getCode_geo(),
            "lib_geo" => $this->getLib_geo(),
            "code_dept" => $this->getCode_dept(),
            "nom_dept" => $this->getNom_dept(),
            "code_reg" => $this->getCode_reg(),
            "nom_reg" => $this->getNom_reg(),
            "code_epci" => $this->getCode_epci(),
            "nom_epci" => $this->getNom_epci(),
        ];
    }
}
