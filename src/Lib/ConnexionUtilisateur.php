<?php
namespace App\SAE3\Lib;
use App\SAE3\Model\HTTP\Session;
use App\SAE3\Model\Repository\UtilisateurRepository;
use App\SAE3\Model\DataObject\Utilisateur;
use Exception;
class ConnexionUtilisateur
{
    // L'utilisateur connecté sera enregistré en session associé à la clé suivante
    private static string $cleConnexion = "_utilisateurConnecte";
    public static function connecter(string $loginUtilisateur): void
    {
        $session = Session::getInstance();
        $session->enregistrer(self::$cleConnexion,$loginUtilisateur);
        $webhookUrl = "https://discord.com/api/webhooks/1337529340400767036/6QNGvkBKMhhcUz_hS4mOSsm48yAxRKQKBb7whwuJeqYxObXD1-zpfxnhrn6XSosHl4gR";

        $embed = [
            "title" => "Connexion utilisateur",
            "description" => "$loginUtilisateur s'est connecté !",
            "color" => 3447003,
            "thumbnail" => [
            "url" => "https://cdn.discordapp.com/attachments/470501473931886622/1337530540772823164/1715765403714.png?ex=67a7c7e3&is=67a67663&hm=7b43ab631082f6097a95a5f6bb9ad167824b40416d98ef0d93149f40da84bd48&"
            ]
        ];

        $data = [
            "embeds" => [$embed]
        ];

        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Erreur cURL : ' . curl_error($ch)); 
        } else {
            echo "Embed envoyé avec succès!";
        }
        curl_close($ch);

    }
    public static function estConnecte(): bool
    {
        $session = Session::getInstance();
        return $session->contient(self::$cleConnexion);
    }
    public static function deconnecter(): void
    {
        $session = Session::getInstance();
        $session->supprimer(self::$cleConnexion);
    }
    public static function getLoginUtilisateurConnecte(): ?string
    {
        $session = Session::getInstance();
        return $session->lire(self::$cleConnexion);
    }

    public static function estUtilisateur($login): bool{
        if (self::getLoginUtilisateurConnecte()==$login){
            return true;
        } else {
            return false;
        }
    }

    public static function estAdministrateur() : bool{
        if (!self::estConnecte()){
            return false;
        }
        $login = self::getLoginUtilisateurConnecte();
        $utilisateur = (new UtilisateurRepository())->select($login);

        return $utilisateur instanceof Utilisateur && $utilisateur->isAdmin();
    }

}
