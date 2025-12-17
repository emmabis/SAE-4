<?php
namespace App\SAE3\model\Repository;

class MessageRepository{
    public static function addMessage(string $room,string $content,string $date,string $author){

        $url = "http://localhost:5000/api/addMsg"; 

        $data = [
            "room" => $room,
            "content" => $content,
            "date" => $date,
            "author" => $author
        ];

        $options = [
            "http" => [
            "header" => "Content-Type: application/json",
            "method" => "POST",
            "content" => json_encode($data),
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        echo $response;

    }
}

?>