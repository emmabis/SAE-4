<?php

namespace App\SAE3\Controller;
use App\SAE3\Lib\PreferenceControleur;
use App\SAE3\Lib\MessageFlash;

class GenericController{
    protected static function afficheVue(string $cheminVueBody, array $parametres = [], string $pagetitle = ''): void {
        $parametres['cheminVueBody'] = $cheminVueBody;
        $parametres['pagetitle'] = $pagetitle;
    
        extract($parametres);
        require __DIR__ . "/../view/view.php";
    }
    public static function error(string $errorMessage){
        self::afficheVue('error.php', ['error' => $errorMessage], 'Erreur');
    }

    protected static function rediriger(string $url, string $typeMessage = '', string $message = ''){
    if ($typeMessage && $message) {
        MessageFlash::ajouter($typeMessage, $message);
    }
    header("Location: $url");
    exit();
    }
}
?>