<?php 

require '../database/model.php';

header("Content-Type: application/json; charset=UTF-8");

$payload = decode_jwt_token(strval($_SERVER["HTTP_X_AUTHORIZATION"]));

if ($payload["is_admin"]) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = json_decode(trim(file_get_contents("php://input")), true);
        $dbaccount = new Account();
        $dbaccount->insert($content["email"], $content["username"]);
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
        $dbaccount = new Account();
        $accountcmd = $dbaccount->get_all();
        $resp = json_encode(array(
            "title" => "OK",
            "description" => "Account list getted successful",
            "content" => array(
                "accounts" => $accountcmd
            )
        ));
        echo $resp;
    }
} else {
    http_response_code(401);
}
?>