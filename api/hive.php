<?php 

require '../database/model.php';

header("Content-Type: application/json; charset=UTF-8");

$payload = decode_jwt_token(strval($_SERVER["HTTP_X_AUTHORIZATION"]));
$content = json_decode(trim(file_get_contents("php://input")), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($payload["is_admin"]) {
        $dbhive = new Hive();
        $dbhive->insert($content["lora_eui"], $content["account_id"], $content["hive_name"]);
    } else {
        http_response_code(401);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    if ($payload["is_admin"]) {
        $dbhive = new Hive();
        $dbhive->del(content["lora_eui"]);
    } else {
        http_response_code(401);
    }
}
?>