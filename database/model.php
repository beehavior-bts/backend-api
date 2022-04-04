<?php

require 'jwt/src/BeforeValidException.php';
require 'jwt/src/ExpiredException.php';
require 'jwt/src/SignatureInvalidException.php';
require 'jwt/src/JWT.php';
require 'jwt/src/Key.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

define("DB_HOST", "by93828-001.privatesql");
define("DB_PORT", 35146);
define("DB_NAME", "prc2022");
define("DB_USER", "prc2022");
define("DB_PASS", "2QU5xsAZBQVNugb");

define("EMAIL_SMTP", "mail.riseup.net");
define("EMAIL_ADDR", "beehavior-service@riseup.net");
define("EMAIL_USER", "beehavior-service");
define("EMAIL_PASS", "=j357un5eYV&Fx$9??RS@bee");

define("JWT_SECRET", "Rywuk8AGyZbDCYSm");

function gen_jwt_token($account_id, $is_admin=false) {
    date_default_timezone_set('UTC');
    $nowtime = time();
    $tk_content = array(
        "iat" => $nowtime,
        "exp" => $nowtime + (60 * 60 * 24 * 14), // 2 weeks
        "uid" => intval($account_id),
        "is_admin" => $is_admin
    );

    $jwt = JWT::encode($tk_content, JWT_SECRET, "HS256");
    return $jwt;
}

function check_jwt_token($token) {
    //
}

function decode_jwt_token($token) {
    $decoded = JWT::decode($token, new Key(JWT_SECRET, "HS256"));
    return $decoded;
}

class DatabaseContext {
    private $user = DB_USER;
    private $password = DB_PASS;
    private $dbname = DB_NAME;
    private $host = DB_HOST;
    private $port = DB_PORT;
    protected $connection = NULL;
    
    public function __construct() {
        try {
            $bdd = new PDO(
                'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->dbname.';charset=utf8', 
                $this->user,
                $this->password
            );
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->connection = $bdd;
        } catch (PDOException $e) {
            $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
			die($msg);
        }
    }
}

class Account extends DatabaseContext {

    private  $pass_char = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "%", "#", "&", "@", "$", "!", "="];

    public function get_info_by_id($account_id) {
        $query = "SELECT t2.id, t2.email, t2.username, t1.id AS hive_id, t1.name AS hive_name 
            FROM hives AS t1 
                INNER JOIN accounts AS t2 ON t1.f_owner = t2.id 
            WHERE t2.id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(1, $account_id);
        $stmt->execute();
    }

    public function get_by_email($email) {
        $query = "SELECT * FROM prc2022.accounts WHERE email = :email";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':email' => $email
        ]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    public function get_all($email) {
        $query = "SELECT * FROM prc2022.accounts";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([
            ':email' => $email
        ]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    public function insert($email, $username) {
        
        // generate password
        $lst_password = [];
        for ($i = 0; $i < 15; $i++) {
            $idx = random_int(0, count($this->pass_char) - 1);
            array_push($lst_password, $this->pass_char[$idx]);
        }
        $raw_password = join("", $lst_password);
        $password = password_hash($raw_password, PASSWORD_BCRYPT);

        $mail = new PHPMailer();
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = EMAIL_SMTP;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Port = 465;
        $mail->Username = EMAIL_USER;                 // SMTP username
        $mail->Password = EMAIL_PASS;                           // SMTP password
        $mail->SMTPSecure = 'ssl';                           // Enable encryption, 'ssl' also accepted
        $mail->From = EMAIL_ADDR;
        $mail->FromName = 'Beehavior Service';
        $mail->addAddress($email);               // Name is optional
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Beehavior Account Credentials';
        $mail->Body    = "<h4>Hey ".$username." ! Here are your credentials for Beehavior website</h4><p>Email : <b>".$email."</b></p><p>Password : <b>".$raw_password."</b></p>";

        if(!$mail->send()) {
            echo $mail->ErrorInfo;
        } else {
            $query = "INSERT INTO prc2022.accounts (email, username, password) VALUES ('".$email."', '".$username."', '".$password."')";
            $stmt = $this->connection->prepare($query);
            if ($stmt->execute())
                return true;
            else
                return false;
        }
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    }
}

class Hive extends DatabaseContext {
    public function insert($lora_eui, $owner_id, $name) {
        $query = "INSERT INTO prc2022.hives (id, f_owner, name) VALUES ('".$lora_eui."', '".$owner_id."', '".$name."')";
        $this->connection->prepare($query);
        $stmt = $this->connection->prepare($query);
        if ($stmt->execute())
            return true;
        else 
            return false;
    }
}

/**
 * Decrit les données monitorer d'une ruche dans la base de données
 */
class Metric extends DatabaseContext {
    public function insert($lora_eui, $humidity, $temp, $mass) {
        $query = "INSERT INTO prc2022.metrics (f_hive, humidity, temperature, mass) VALUES ('".$lora_eui."', '".$humidity."', '".$temp."', '".$mass."')";
        $stmt = $this->connection->prepare($query);
        if ($stmt->execute())
            return true;
        else 
            return false;
    }
}
?>