<?php
namespace App\SAE3\Lib;
use App\SAE3\Model\HTTP\Cookie;

class MeteothequeDefaut {
    private static string $clePreference = "meteothequeDefaut";
    public static function enregistrer(string $preference) : void
    {
        Cookie::enregistrer(static::$clePreference, $preference,3600);
    }
    public static function lire() : string
    {
        if (Cookie::contient(static::$clePreference)){
            return Cookie::lire(static::$clePreference);
        }
    return "";
    }
    public static function existe() : bool
    {
        return Cookie::contient(static::$clePreference);
    }
    public static function supprimer() : void
    {
        Cookie::supprimer(static::$clePreference);
    }
}
?>