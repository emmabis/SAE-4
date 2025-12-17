<?php
namespace App\SAE3\Config;

class Conf {
    static private array $databases = array(
        // Chemin absolu vers le fichier de la base de données SQLite
        'database_path' => __DIR__ . '/../DB/synop.db', // Chemin relatif à ce fichier
    );

    static public function getDatabasePath() : string {
        return static::$databases['database_path'];
    }
}
?>
