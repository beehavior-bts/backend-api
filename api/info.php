<?php 

require '../database/model.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 3000');
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, X-Authorization");

$payload = decode_jwt_token(strval($_SERVER["HTTP_X_AUTHORIZATION"]));

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $dbaccount = new Account();
    $dbhive = new Hive();
    $dbalert = new Alert();

    $infocmd = $dbaccount->get_info_by_id($payload["uid"]);
    $infohive = $dbhive->get_info_by_owner($payload["uid"]);
    $alertcmd = $dbalert->get_info_by_owner($payload["uid"]);

    for ($i=0; $i<count($infohive); $i++) {
        $infohive[$i]["alerts"] = array();
    }

    for ($a=0; $a<count($alertcmd); $a++) {
        $alertcmd[$a]["id"] = intval($alertcmd[$a]["id"]);
        $alertcmd[$a]["value"] = floatval($alertcmd[$a]["value"]);
        for ($b=0; $b<count($infohive); $b++) {
            if ($infohive[$b]["id"] == $alertcmd[$a]["hive"]) {
                array_push($infohive[$b]["alerts"], $alertcmd[$a]);
            }
        }
    }

    $infocmd["id"] = intval($infocmd["id"]);
    $infocmd["hives"] = $infohive;
    $infocmd["is_admin"] = boolval($infocmd["is_admin"]);

    $resp = json_encode(array(
        "title" => "OK",
        "description" => "Info getted successful",
        "content" => $infocmd
    ));
    echo $resp;
}
?>
