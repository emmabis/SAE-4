<?php
require __DIR__ . '/../vendor/autoload.php';

use App\SAE3\Controller\GenericController;
use App\SAE3\Controller\ControllerMeteotheque;
use App\SAE3\Lib\MeteothequeDefaut;

// On recupère l'action passée dans l'URL

if (isset($_GET['controller'])){
    $controller = $_GET['controller'];
    $controllerClassName = "App\SAE3\Controller\Controller".ucfirst($controller);
    if(isset($_GET['action'])){
        $action = $_GET['action'];
    if (method_exists($controllerClassName, $action)) {
        $controllerClassName::$action();
    } else {
        GenericController::error("Methode non reconnu");
        var_dump($controllerClassName);
    }
    }else{
        GenericController::error("Veuillez mettre une action");
    }
} else {
    if (MeteothequeDefaut::existe()){
        $meteothequeDefaut = MeteothequeDefaut::lire();
        $url = "../web/frontController.php?action=read&controller=meteotheque&id_meteotheque=$meteothequeDefaut";
        header("Location: $url");
    }
}

