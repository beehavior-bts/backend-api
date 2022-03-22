<?php

require '../database/model.php';

header("Content-Type: application/json; charset=UTF-8");
// $req = json_decode($_POST["laul"], false);
$content = json_decode(trim(file_get_contents("php://input")), true);
/* $content = json_decode($_POST, true);
var_dump($content); */
// $data = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$lst_password = [];
for ($i = 0; $i < 15; $i++) {
    $idx = random_int(0, count($this->pass_char) - 1);
    array_push($lst_password, $this->pass_char[$idx]);
}
$raw_password = join("", $lst_password);
$fp = fopen('content.txt', 'w');
fwrite($fp, $raw_password);
fclose($fp);
$password = NULL;

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
$dbaccount = new Account();
$fp = fopen('content.txt', 'w');
fwrite($fp, $_SERVER["REQUEST_METHOD"]);
fclose($fp);


$dbaccount->insert("");

$resp = json_encode(array(
    "title" => "SAVED",
    "description" => "Metric has been saved in database",
    "content" => $content["laul"]
));
echo $resp;
?>

