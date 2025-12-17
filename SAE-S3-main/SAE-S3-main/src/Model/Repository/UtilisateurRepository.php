<?php
namespace App\SAE3\model\Repository;
use App\SAE3\model\DataObject\Utilisateur;

class UtilisateurRepository extends AbstractRepository{

    public function construire(array $utilisateurFormatTableau): Utilisateur {
        return new Utilisateur(
            
            $utilisateurFormatTableau['login'],
            $utilisateurFormatTableau['mdpHache'],
            $utilisateurFormatTableau['estAdmin']
        );
    }

    protected function getNomTable(): string
    {
        return 'utilisateur';
    }

    protected function getNomClePrimaire(): string {
        return 'login';
    }

    protected function getNomsColonnes(): array {
        return ['login', 'mdpHache','estAdmin'];
    }

}