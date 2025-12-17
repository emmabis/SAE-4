<?php
namespace App\SAE3\model\DataObject;

use App\SAE3\Lib\MotDePasse;

class Utilisateur extends AbstractDataObject {
    private string $login;
    private string $mdpHache;
    private int $estAdmin;

    public function getLogin(): string {
        return $this->login;
    }
    public function setLogin(string $login): void {
        $this->login = $login;
    }

    public function getMdpHache(): string {
        return $this->mdpHache;
    }
    public function setMdpHache(string $mdpClair): void {
        $this->mdpHache = MotDePasse::hacher($mdpClair);
    }

    public function isAdmin(): bool {
        return $this->estAdmin === 1;
    }
    public function setAdmin(bool $isAdmin): void {
        $this->estAdmin = $isAdmin ? 1 : 0;
    }

    public function __construct(
        string $login,
        string $mdpHache,
        int $estAdmin
    ) {
        $this->login = $login;
        $this->mdpHache = $mdpHache;
        $this->estAdmin = $estAdmin;
    }

    public static function construireDepuisFormulaire(array $tableauFormulaire): Utilisateur {
        $login = $tableauFormulaire['login'];
        $mdpHache = MotDePasse::hacher($tableauFormulaire['mdp']);
        $estAdmin = isset($tableauFormulaire['estAdmin']) ? 1 : 0;

        return new Utilisateur($login, $mdpHache, $estAdmin);
    }

    public function formatTableau(): array {
        return [
            "loginTag" => $this->getLogin(),
            "mdpHacheTag" => $this->getMdpHache(),
            "estAdminTag" => $this->estAdmin? 1 : 0,
        ];
    }
}
