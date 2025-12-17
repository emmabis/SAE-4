<?php
namespace App\SAE3\model\HTTP;
class Cookie {
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void{
        setcookie($cle, serialize($valeur), time() + $dureeExpiration);
    }

    public static function lire(string $cle): mixed{
        return unserialize($_COOKIE[$cle]);
    }

    public static function contient($cle) : bool{
        return array_key_exists($cle, $_COOKIE);
    }

    public static function supprimer($cle) : void{
        unset($_COOKIE[$cle]);
    }
}