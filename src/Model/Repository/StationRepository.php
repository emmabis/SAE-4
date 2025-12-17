<?php
namespace App\SAE3\model\Repository;

use App\SAE3\model\Repository\DatabaseConnection;
use App\SAE3\model\DataObject\Station;

class StationRepository extends AbstractRepository{

    public function construire(array $objetFormatTableau): Station {
        return new Station(
            $objetFormatTableau['id_station'],
            $objetFormatTableau['nom'],
            $objetFormatTableau['latitude'],
            $objetFormatTableau['longitude'],
            $objetFormatTableau['altitude'],
            $objetFormatTableau['code_geo'],
            $objetFormatTableau['lib_geo'],
            $objetFormatTableau['code_dept'],
            $objetFormatTableau['nom_dept'],
            $objetFormatTableau['code_reg'],
            $objetFormatTableau['nom_reg'],
            $objetFormatTableau['code_epci'],
            $objetFormatTableau['nom_epci']
        );
    }
    

    protected function getNomTable(): string
    {
        return 'stations';
    }

    protected function getNomClePrimaire(): string {
        return 'id_station';
    }

    protected function getNomsColonnes(): array {
        return [
            'id_station',
            'nom',
            'latitude',
            'longitude',
            'altitude',
            'code_geo',
            'lib_geo',
            'code_dept',
            'nom_dept',
            'code_reg',
            'nom_reg',
            'code_epci',
            'nom_epci'
        ];
    }
    

}