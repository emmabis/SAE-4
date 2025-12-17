<?php
namespace App\SAE3\Lib;

use App\SAE3\Model\HTTP\Session;

class MessageFlash
{
    private static string $cleFlash = "_messagesFlash";

    public static function ajouter(string $type, string $message): void
    {
        $session = Session::getInstance();
        $messages = $session->lire(self::$cleFlash) ?? [];
        $messages[$type][] = $message;
        $session->enregistrer(self::$cleFlash, $messages);
    }

    public static function contientMessage(string $type): bool
    {
        $session = Session::getInstance();
        $messages = $session->lire(self::$cleFlash) ?? [];
        return !empty($messages[$type] ?? []);
    }

    public static function lireMessages(string $type): array
    {
        $session = Session::getInstance();
        $messages = $session->lire(self::$cleFlash) ?? [];
        $typeMessages = $messages[$type] ?? [];
        unset($messages[$type]);
        $session->enregistrer(self::$cleFlash, $messages);
        return $typeMessages;
    }

    public static function lireTousMessages(): array
    {
        $session = Session::getInstance();
        $messages = $session->lire(self::$cleFlash) ?? [];
        $session->supprimer(self::$cleFlash);
        return $messages;
    }
}
