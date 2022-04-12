<?php

require '../../database/model.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 3000');
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, X-Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = json_decode(trim(file_get_contents("php://input")), true);
    $dbaccount = new Account();
    $account = $dbaccount->get_by_email($content["email"]);
    if (password_verify($content["password"], $account["password"])) {
        $token = gen_jwt_token($account["id"], $account["is_admin"]);
        $resp = json_encode(array(
            "title" => "OK",
            "description" => "Token has been generated",
            "content" => array(
                "token" => $token
            )
        ));
        echo $resp;
    } else {
        http_response_code(400);
    }
}
?>