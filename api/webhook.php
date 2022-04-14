<?php

require '../database/model.php';

header("Content-Type: application/json; charset=UTF-8");
// $req = json_decode($_POST["laul"], false);
$content = json_decode(trim(file_get_contents("php://input")), true);

$end_device_id = strtoupper(explode("-", $content["end_device_ids"]["device_id"])[1]);
$received_at = $content["received_at"];
$payload = $content["uplink_message"]["frm_payload"];

// "humidity|temperature|mass"
$dec_payload = base64_decode($payload);
$metrics = explode("|", $dec_payload);
$humidity = intval($metrics[0]);
$temperature = floatval($metrics[1]);
$mass = floatval($metrics[2]);

$dbmetric = new Metric();
$dbmetric->insert($end_device_id, $humidity, $temperature, $mass);
createToken();

// $dbaccount = new Account();
// $dbaccount->insert("ristich.esteban.lgm@gmail.com", "Estéban");
/*
$dbaccount = new Account();
*/

$resp = json_encode(array(
    "title" => "SAVED",
    "description" => "Metric has been saved in database",
));
echo $resp;
?>
