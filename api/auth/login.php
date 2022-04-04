<?php

require '../../database/model.php';

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = json_decode(trim(file_get_contents("php://input")), true);
    $dbaccount = new Account();
    $account = $dbaccount->get_by_email($content["email"]);
    if (password_verify($content["password"], $account["password"])) {
        $token = gen_jwt_token($account["id"]);
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