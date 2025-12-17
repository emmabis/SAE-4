<?php

namespace App\SAE3\model\Repository;

use PDO;
use App\SAE3\model\Repository\DatabaseConnection;
use App\SAE3\model\DataObject\AbstractDataObject;

abstract class AbstractRepository{

    protected abstract function getNomTable(): string;

    public function selectAll(): array{
        $pdo = DatabaseConnection::getPdo();
        $nomTable = $this->getNomTable();
        $table = [];
        $query = "SELECT * FROM $nomTable";
        $pdoStatement = $pdo->query($query);
        foreach ($pdoStatement as $objetFormatTableau) {
            $table[] = $this->construire($objetFormatTableau);
        }
        
        return $table;
    }

    public function selectAllWhere(string $nomCle,string $valeurCle): array{
        $pdo = DatabaseConnection::getPdo();
        $nomTable = $this->getNomTable();
        $table = [];
        $query = "SELECT * FROM $nomTable WHERE $nomCle = :valeurCle";
        $pdoStatement = $pdo->prepare($query);
        $pdoStatement->execute(['valeurCle' => $valeurCle]);
        foreach ($pdoStatement as $objetFormatTableau) {
            $table[] = $this->construire($objetFormatTableau);
        }
        
        return $table;
    }
    
    public function selectAllWhereMultiple(array $conditions): array {
        $pdo = DatabaseConnection::getPdo();
        $nomTable = $this->getNomTable();
        $table = [];
    
        $whereClauses = [];
        $params = [];
        foreach ($conditions as $colonne => $valeur) {
            $whereClauses[] = "$colonne = :$colonne";
            $params[$colonne] = $valeur;
        }
    
        $whereClause = implode(" AND ", $whereClauses);
        $query = "SELECT * FROM $nomTable WHERE $whereClause";
    
        $pdoStatement = $pdo->prepare($query);
        $pdoStatement->execute($params);
    
        foreach ($pdoStatement as $objetFormatTableau) {
            $table[] = $this->construire($objetFormatTableau);
        }
    
        return $table;
    }
    

    protected abstract function construire(array $objetFormatTableau): AbstractDataObject;

    public function select(string $valeurClePrimaire): ?AbstractDataObject {
        $pdo = DatabaseConnection::getPdo();
        $nomTable = $this->getNomTable();  
        $nomClePrimaire = $this->getNomClePrimaire();
        $sql = "SELECT * FROM $nomTable WHERE $nomClePrimaire = :valeurClePrimaire";
        $pdoStatement = $pdo->prepare($sql);
        $pdoStatement->execute(['valeurClePrimaire' => $valeurClePrimaire]);

        $objetFormatTableau = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        if ($objetFormatTableau === false) {
            return null;
        }

        return $this->construire($objetFormatTableau);
    }

    public function Supprimer(string $valeurClePrimaire): void {
        $nomTable = $this->getNomTable();  
        $nomClePrimaire = $this->getNomClePrimaire();
        $DeleteSql = "DELETE FROM $nomTable WHERE $nomClePrimaire = :valeurClePrimaire";
        $pdoStatement = DatabaseConnection::getPdo()->prepare($DeleteSql);
        $pdoStatement->execute([
            'valeurClePrimaire' => $valeurClePrimaire
        ]);
       }

       public function deleteWhereMultiple(array $conditions): void {
        $pdo = DatabaseConnection::getPdo();
        $nomTable = $this->getNomTable();
    
        $whereClauses = [];
        $params = [];
        foreach ($conditions as $colonne => $valeur) {
            $whereClauses[] = "$colonne = :$colonne";
            $params[$colonne] = $valeur;
        }
    
        $whereClause = implode(" AND ", $whereClauses);
        $query = "DELETE FROM $nomTable WHERE $whereClause";
    
        $pdoStatement = $pdo->prepare($query);
        $pdoStatement->execute($params);
    }
    

    public function sauvegarder(AbstractDataObject $objet): void {
        $pdo = DatabaseConnection::getPdo();
        $nomTable = $this->getNomTable();
        $nomsColonnes = $this->getNomsColonnes();

        $params = $objet->formatTableau();

        $columns = implode(", ", $nomsColonnes);
        $placeholders = implode(", ", array_map(fn($col) => ":{$col}Tag", $nomsColonnes));
        
        $query = "INSERT INTO $nomTable ($columns) VALUES ($placeholders)";

        $pdoStatement = $pdo->prepare($query);
        $pdoStatement->execute($params);
    }


public function update(AbstractDataObject $object): void {
    $pdo = DatabaseConnection::getPdo();
    $nomTable = $this->getNomTable();
    $nomClePrimaire = $this->getNomClePrimaire();
    $nomsColonnes = $this->getNomsColonnes();
    $params = $object->formatTableau();
    $setParts = [];

    foreach ($nomsColonnes as $colonne) {
        $setParts[] = "$colonne = :{$colonne}Tag";
    }

    $setClause = implode(", ", $setParts);

    if (!isset($params["{$nomClePrimaire}Tag"])) {
        $params["{$nomClePrimaire}Tag"] = $object->{"get" . ucfirst($nomClePrimaire)}();
    }

    $query = "UPDATE $nomTable SET $setClause WHERE $nomClePrimaire = :{$nomClePrimaire}Tag";
    $params["{$nomClePrimaire}Tag"] = $object->{"get" . ucfirst($nomClePrimaire)}();

    $pdoStatement = $pdo->prepare($query);
    $pdoStatement->execute($params);
}
    
    protected abstract function getNomsColonnes(): array;
    protected abstract function getNomClePrimaire(): string;
}