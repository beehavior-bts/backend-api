<?php 

require '../database/model.php';

header("Content-Type: application/json; charset=UTF-8");

// $payload = decode_jwt_token(strval($_SERVER["HTTP_X_AUTHORIZATION"]));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = json_decode(trim(file_get_contents("php://input")), true);
    $dbalert = new Alert();
    echo "BREAK #1";
    $dbhive = new Hive();
    // $hiveinfo = $dbhive->get_info_by_id($content["hive_id"]);
    /*
    echo "BREAK #2";
    if (intval($hiveinfo["f_owner"]) == intval($payload["uid"])) {
        echo "BREAK #3";
        $dbalert->insert(strval($content["hive_id"]), strval($content["rule"]), floatval($content["value"]));
        echo "BREAK #4";
        // ENUM('HUMIDITY_LESS_THAN', 'HUMIDITY_MORE_THAN', 'TEMPERATURE_LESS_THAN', 'TEMPERATURE_MORE_THAN', 'MASS_LESS_THAN', 'MASS_MORE_THAN')
    } else {
	http_response_code(400);
    }
    */
    $dbalert->insert(strval($content["hive_id"]), strval($content["rule"]), floatval($content["value"]));
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    $dbalert = new Alert();
    $res = $dbalert->get_info_by_hive($_GET["hive"]);
    $resp = json_encode(array(
	    "title" => "SAVED",
	    "description" => "Metric has been saved in database",
	    "content" => $res
    ));
    echo $resp;
}

?>
