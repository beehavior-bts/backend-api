<?php 

require '../database/model.php';

header("Content-Type: application/json; charset=UTF-8");

$payload = decode_jwt_token(strval($_SERVER["HTTP_X_AUTHORIZATION"]));

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $dbaccount = new Account();
    $dbhive = new Hive();

    $infocmd = $dbaccount->get_info_by_id($payload["uid"]);
    $infohive = $dbhive->get_info_by_owner($payload["uid"]);

    $infocmd["hives"] = $infohive;

    $resp = json_encode(array(
        "title" => "OK",
        "description" => "Info getted successful",
        "content" => $infocmd
    ));
    echo $resp;
}
?>