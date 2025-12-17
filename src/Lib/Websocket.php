<?php
namespace App\SAE3\Lib;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\SAE3\model\Repository\MessageRepository;
use App\SAE3\Lib\ConnexionUtilisateur;
use Exception;

class Websocket implements MessageComponentInterface {
    protected $clients;
    protected $rooms = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $conn, $msg) {
        $data = json_decode($msg, true);
        var_dump($data);
        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'join':
                    $roomId = $data['room'];
                    if (!isset($this->rooms[$roomId])) {
                        $this->rooms[$roomId] = new \SplObjectStorage();
                    }
                    $this->rooms[$roomId]->attach($conn);
                    break;
                case 'leave':
                    $roomId = $data['room'];
                    if (isset($this->rooms[$roomId])) {
                        $this->rooms[$roomId]->detach($conn);
                        if ($this->rooms[$roomId]->count() === 0) {
                            unset($this->rooms[$roomId]);
                        }
                    }
                    break;
                case 'msg':
                    $roomId = $data['room'];
                    $msg = $data['msg'];
                    $date = $data['date'];
                    $author = $data['author'];
                    $rawData = array('msg' => $msg, 'room' => $roomId);
                    $data = json_encode($rawData);
                    if (isset($this->rooms[$roomId])) {
                        foreach($this->rooms[$roomId] as $client){
                            $client->send($data);
                        }
                        MessageRepository::addMessage($roomId,$msg,$date,$author);
                        
                        // Pour déboguer, envoyez toujours le webhook
                        error_log("Author: $author, RoomId: $roomId");
                        if ($author == $roomId) {
                             WebhookDiscord($author, $msg);
                        }
                    }
                    break;
                case 'announce':
                    $msg = $data['msg'];
                    foreach($this->clients as $client){
                        $client->send($msg);
                    }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}

function WebhookDiscord(string $client, string $content) {
    
    $webhookUrl = "https://discord.com/api/webhooks/1356969794573828204/RE9MmVIDs3Y1dH76cSKGIhyYeOA0DrFKgGZKgUJ6-zZva3O8lBLm6X1VHw5puggNF49Q";

    $embed = [
        "title" => "Support utilisateur",
        "description" => "$client demande : $content",
        "color" => 3447003,
        "thumbnail" => [
            "url" => "https://cdn.discordapp.com/attachments/470501473931886622/1337530540772823164/1715765403714.png?ex=67a7c7e3&is=67a67663&hm=7b43ab631082f6097a95a5f6bb9ad167824b40416d98ef0d93149f40da84bd48&"
        ]
    ];

    $data = [
        "embeds" => [$embed]
    ];

    // Log des données envoyées
    error_log("Données webhook: " . json_encode($data));

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
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    error_log("Réponse webhook - Code: $httpCode, Réponse: $response");
    
    $result = true;
    if (curl_errno($ch)) {
        $errorMsg = 'Erreur cURL : ' . curl_error($ch);
        error_log($errorMsg);
        $result = false;
    } else {
        error_log("Webhook envoyé avec succès!");
    }
    curl_close($ch);
    return $result;
}