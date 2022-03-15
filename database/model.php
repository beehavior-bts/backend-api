<?php

class Model {
    private $user = "prc2022";
    private $password = "2QU5xsAZBQVNugb";
    private $dbname = "prc2022";
    private $host = "by93828-001.privatesql";
    private $port = "35146"
    private $connection;
    
    public function __construct() {
        try {
            $bdd = new PDO(
                'mysql:host='.$this->host.';port='.$this->port.';dbname='.$this->dbname.';charset=utf8', 
                $this->username, 
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

?>