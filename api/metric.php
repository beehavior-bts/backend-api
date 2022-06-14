<?php
require '../database/model.php';
header("content-type: application/json; charset=UTF-8");

header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 3000');
header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE, PUT, UPDATE");
header("Access-Control-Allow-Headers: Access-Control-Request-Header, Access-Control-Request-Origin, Content-Type, Accept, Vary, Host, X-Requested-With, Referer, Origin, Corentin");

// $payload = decode_jwt_token(strval($_SERVER["HTTP_CORENTIN"]));

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$dbmetric = new Metric();
	$start = $_GET["start"];
	$end = $_GET["end"];
	$res = array();
	if (!isset($start) || !isset($end) || $start == '' || $end == '' || $start == null || $end == null) {
	    $res = $dbmetric->getData($_GET["hive"]);
	} else {
	    $res = $dbmetric->get_by_interval($_GET["hive"], $start, $end);
	}
	// $res = $dbmetric->getData($_GET["hive"], $start, $end);
	// echo "hive : ".$_GET["hive"];
	// var_dump($res);

        for ($i=0; $i<count($res); $i++) {
       	    $res[$i]["id"] = intval($res[$i]["id"]);
	    $res[$i]["humidity"] = intval($res[$i]["humidity"]);
            $res[$i]["temperature"] = floatval($res[$i]["temperature"]);
            $res[$i]["mass"] = floatval($res[$i]["mass"]);
    	}

	$resp = json_encode(array(
	    "title" => "SAVED",
	    "nb_items" => count($res),
	    "start" => $start,
	    "end" => $end,
	    "description" => "Metric has been saved in database",
	    "content" => $res
	));
	echo $resp;
}
?>
