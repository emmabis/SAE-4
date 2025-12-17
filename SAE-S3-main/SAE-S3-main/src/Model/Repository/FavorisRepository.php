<?php
namespace App\SAE3\model\Repository;

use App\SAE3\model\DataObject\Favoris;

class FavorisRepository extends AbstractRepository {

    // Méthode pour construire un objet Favori depuis un tableau associatif
    public function construire(array $favoriFormatTableau): Favoris {
        return new Favoris(
            $favoriFormatTableau['id_meteotheque'],
            $favoriFormatTableau['utilisateur']
        );
    }

    protected function getNomTable(): string {
        return 'favoris';
    }

    protected function getNomClePrimaire(): string {
        return 'utilisateur';
    }

    protected function getNomsColonnes(): array {
        return ['id_meteotheque', 'utilisateur'];
    }
}
