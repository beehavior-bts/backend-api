<?php 

require '../database/model.php';

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 3000');
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, X-Authorization");

$payload = decode_jwt_token(strval($_SERVER["HTTP_X_AUTHORIZATION"]));

if ($payload["is_admin"]) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $content = json_decode(trim(file_get_contents("php://input")), true);
        $dbaccount = new Account();
        $dbaccount->insert($content["email"], $content["username"]);
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
        $dbaccount = new Account();
        $dbhive = new Hive();
        $hivecmd = $dbhive->get_all();
        $accountcmd = $dbaccount->get_all();

        for ($i=0; $i<count($accountcmd); $i++) {
            $accountcmd[$i]["id"] = intval($accountcmd[$i]["id"]);
            $accountcmd[$i]["hives"] = array();
        }

        for ($h=0; $h<count($hivecmd); $h++) {
            $hivecmd[$h]["owner"] = intval($hivecmd[$h]["owner"]);
            for ($a=0; $a<count($accountcmd); $a++) {
                if ($accountcmd[$a]["id"] == $hivecmd[$h]["owner"]) {
                    array_push($accountcmd[$a]["hives"], $hivecmd[$h]);
                }
            }
        }
        $resp = json_encode(array(
            "title" => "OK",
            "description" => "Account list getted successful",
            "content" => array(
                "accounts" => $accountcmd
            )
        ));
        echo $resp;
    } elseif ($_SERVER["REQUEST_METHOD"] == "DELETE") {
	$content = json_decode(trim(file_get_contents("php://input")), true);
        $dbaccount = new Account();
        $dbaccount->del($content['id']);
    }
} else {
    http_response_code(401);
}
?>