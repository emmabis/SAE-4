<?php
namespace App\SAE3\Model\HTTP;

use Exception;

class Session
{
    private static ?Session $instance = null;

    /**
     * @throws Exception
     */

    private const DELAI_EXPIRATION = 3600;
    private function __construct()
    {
        if (session_start() === false) {
            throw new Exception("La session n'a pas réussi à démarrer.");
        }
    }

    public static function getInstance(): Session
    {
        if (is_null(static::$instance)) {
            static::$instance = new Session();
            static::$instance->verifierDerniereActivite();
        }
        return static::$instance;
    }

    public function contient($name): bool
    {
        return isset($_SESSION[$name]);
    }

    public function enregistrer(string $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    public function lire(string $name): mixed
    {
        return $this->contient($name) ? $_SESSION[$name] : null;
    }

    public function supprimer($name): void
    {
        if ($this->contient($name)) {
            unset($_SESSION[$name]);
        }
    }

    public function detruire(): void
    {
        session_unset(); // unset $_SESSION variable for the run-time
        session_destroy(); // destroy session data in storage
        Cookie::supprimer(session_name()); // deletes the session cookie
        // Il faudra reconstruire la session au prochain appel de getInstance()
        static::$instance = null;
    }

    private function verifierDerniereActivite(): void
    {
        $tempsActuel = time();

        if (isset($_SESSION['derniereActivite'])) {
            $inactivite = $tempsActuel - $_SESSION['derniereActivite'];
            if ($inactivite > self::DELAI_EXPIRATION) {
                $this->detruire();
                throw new Exception("Votre session a expiré en raison d'une inactivité prolongée.");
            }
        }

        $_SESSION['derniereActivite'] = $tempsActuel;
    }

}
