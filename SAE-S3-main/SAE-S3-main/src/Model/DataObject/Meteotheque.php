<?php

namespace App\SAE3\model\DataObject;
class Meteotheque extends AbstractDataObject{
    private ?int $id_meteotheque;
    private string $titre;
    private string $localisation;
    private string $date_debut;
    private string $date_fin;
    private string $utilisateur;
    private int $prive;
    private ?string $type_localisation;

    public function __construct(
        string $titre,
        string $localisation,
        string $date_debut,
        string $date_fin,
        string $utilisateur,
        bool $prive,
        ?string $type_localisation,
        ?int $id_meteotheque = null
    ) {
        $this->id_meteotheque = $id_meteotheque;
        $this->titre = $titre;
        $this->localisation = $localisation;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->utilisateur = $utilisateur;
        $this->prive = $prive;
        $this->type_localisation = $type_localisation ?? null;
    }

    public function getId_meteotheque(): ?int {
        return $this->id_meteotheque;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getLocalisation(): string {
        return $this->localisation;
    }

    public function getDateDebut(): string {
        return $this->date_debut;
    }

    public function getDateFin(): string {
        return $this->date_fin;
    }

    public function getUtilisateur(): string {
        return $this->utilisateur;
    }

    public function getPrive(): bool {
        return $this->prive === 1;
    }

    public function getTypeLocalisation(): string {
        return $this->type_localisation;
    }

    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function setLocalisation(string $localisation): void {
        $this->localisation = $localisation;
    }

    public function setDateDebut(string $date_debut): void {
        $this->date_debut = $date_debut;
    }

    public function setDateFin(string $date_fin): void {
        $this->date_fin = $date_fin;
    }

    public function setUtilisateur(string $utilisateur): void {
        $this->utilisateur = $utilisateur;
    }

    public function setPrive(bool $prive): void {
        $this->prive = $prive ? 1 : 0;
    }

    public function setTypeLocalisation(string $type_localisation): void {
        $this->type_localisation = $type_localisation;
    }

    public function formatTableau(): array {
        return [
            "titreTag" => $this->getTitre(),
            "localisationTag" => $this->getLocalisation(),
            "date_debutTag" => $this->getDateDebut(),
            "date_finTag" => $this->getDateFin(),
            "utilisateurTag" => $this->getUtilisateur(),
            "priveTag" => $this->getPrive() ? 1 : 0,
            "type_localisationTag" => $this->getTypeLocalisation()
        ];
    }
    
    
    public static function construireDepuisFormulaire(array $tableauFormulaire,string $utilisateur): Meteotheque {
        $titre = $tableauFormulaire['titre'];
        $localisation = $tableauFormulaire['localisation'];
        $dateDebut = $tableauFormulaire['date_debut'];
        $dateFin = $tableauFormulaire['date_fin'];
        $prive = isset($tableauFormulaire['prive']) ? 1 : 0;
        $typeLocalisation = $tableauFormulaire['type_localisation'];
    
        return new Meteotheque($titre, $localisation, $dateDebut, $dateFin, $utilisateur, $prive,$typeLocalisation);
    }

}
