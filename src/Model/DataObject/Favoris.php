<?php
namespace App\SAE3\model\DataObject;

class Favoris extends AbstractDataObject{
    private int $idMeteotheque;
    private string $utilisateur; 

    public function __construct(int $idMeteotheque, string $utilisateur) {
        $this->idMeteotheque = $idMeteotheque;
        $this->utilisateur = $utilisateur;
    }

    public function getIdMeteotheque(): int {
        return $this->idMeteotheque;
    }

    public function setIdMeteotheque(int $idMeteotheque): void {
        $this->idMeteotheque = $idMeteotheque;
    }

    public function getUtilisateur(): string {
        return $this->utilisateur;
    }

    public function setUtilisateur(string $utilisateur): void {
        $this->utilisateur = $utilisateur;
    }

    public function formatTableau(): array {
        return [
            'id_meteothequeTag' => $this->idMeteotheque,
            'utilisateurTag' => $this->utilisateur,
        ];
    }
}
