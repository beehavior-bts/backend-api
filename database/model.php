<?php

class DatabaseContext {
    private $user = "prc2022";
    private $password = "2QU5xsAZBQVNugb";
    private $dbname = "prc2022";
    private $host = "by93828-001.privatesql";
    private $port = "35146";
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

    private const pass_char = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "%", "#", "&", "@", "$", "!", "="];

    public function insert($email, $username) {
        
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

        $query = "INSERT INTO prc2022.accounts (email, username, password) VALUES ('".$email."', '".$username."', '".$password."', '".$mass."')";
        $stmt = $this->connection->prepare($query);
            if ($stmt->execute())
                return true;
            else 
                return false;
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