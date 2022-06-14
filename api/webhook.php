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

/*
$dbalert = new Alert();
$dbhive = new Hive();
$dbaccount = new Account();
$owner_email = $dbaccount->get_info_by_id($dbhive->get_info_by_id($end_device_id)["f_owner"])["email"];
$r = $dbalert->get_info_by_hive($end_device_id);
*/

$dbmetric = new Metric();
$fp = fopen("metrictest.txt", "w");
$insert_time = explode(".", $received_at)[0];
$aze = join(" ", explode("T", $insert_time));
fwrite($fp, "device_id : ".$end_device_id."\nhumidite : ".$humidity."\ntemperature : ".$temperature."\nmass : ".$mass."\ndate : ".$received_at."\ntime explode : ".$aze);
fclose($fp);

$dbmetric->insert($end_device_id, $humidity, $temperature, $mass, $aze);
// createToken();

// $dbaccount = new Account();
// $dbaccount->insert("ristich.esteban.lgm@gmail.com", "Estéban");
/*
$dbaccount = new Account();
*/

$resp = json_encode(array(
    "title" => "SAVED",
    "description" => "Metric has been saved in database"
));
// var_dump($r);
echo $resp;
?>
