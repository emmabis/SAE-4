<?php
namespace App\SAE3\model\Repository;
use App\SAE3\model\DataObject\Meteotheque;

class MeteothequeRepository extends AbstractRepository{

    public function construire(array $MeteothequeFormatTableau): Meteotheque {
        return new Meteotheque(
            $MeteothequeFormatTableau['titre'],
            $MeteothequeFormatTableau['localisation'],
            $MeteothequeFormatTableau['date_debut'],
            $MeteothequeFormatTableau['date_fin'],
            $MeteothequeFormatTableau['utilisateur'],
            $MeteothequeFormatTableau['prive'],
            
            $MeteothequeFormatTableau['type_localisation'],
            $MeteothequeFormatTableau['id_meteotheque'],
        );
    }

    protected function getNomTable(): string
    {
        return 'meteotheque';
    }

    protected function getNomClePrimaire(): string {
        return 'id_meteotheque';
    }
    
    protected function getNomsColonnes(): array {
        return ['titre','localisation','date_debut','date_fin','utilisateur','prive','type_localisation'];
    }

}