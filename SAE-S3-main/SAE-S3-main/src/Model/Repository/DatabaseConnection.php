<?php 
namespace App\SAE3\model\Repository;

use PDO;
use App\SAE3\Config\Conf;

class DatabaseConnection {
    private $pdo;
    private static $instance = null;

    public static function getPdo(): PDO {
        return static::getInstance()->pdo;
    }

    public function __construct() {	
        $databasePath = Conf::getDatabasePath();

        $this->pdo = new PDO("sqlite:$databasePath");

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    private static function getInstance() {
        if (is_null(static::$instance)) {
            static::$instance = new DatabaseConnection();
        }
        return static::$instance;
    }
}
?>
