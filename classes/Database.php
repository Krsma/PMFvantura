<?php
require_once("configs/constants.php");

class Database
{
    private $conn;
    private $hashSalt="dsaf7493^&$(#@Kjh";
    // TEMPORARY implementiraj dynamic hashing

    public function __construct($configPath = "config.ini")
    {
        if($config = parse_ini_file($configPath))
        {
            $host = $config["host"];
            $database = $config["database"];
            $user = $config["user"];
            $password = $config["password"];

//            $this->hashSalt = $config["salt"];
            $this->conn = new PDO("mysql:host=$host;dbname=$database", $user, $password );
        }
        else
        {
            exit("Failed to load config file");
        }
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    public function __destruct()
    {
        $this->conn = null;
    }

    public function insertUser($username, $password, $email)
    {
        $sqlQuery = "INSERT INTO " . TBL_USER . " (" . COL_USER_USERNAME . ", " . COL_USER_PASSWORD . ", " . COL_USER_EMAIL . ") VALUES (:username, :password, :email);";

        try {

            if($this->checkIfUserExists($username))
            {
                echo "Vec postoji korisnik";
                return false;
            }
            //DOdaje provere da li vec postoji user

            $query = $this->conn->prepare($sqlQuery);
            $query->bindValue(":username", $username);
            $query->bindValue(":password", crypt($password, $this->hashSalt));
            $query->bindValue(":email", $email);

            $query->execute();
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
        return true;
    }
    public function checkIfUserExists($username)
    {
        $sqlQuery = "SELECT * FROM  " . TBL_USER . " WHERE " . COL_USER_USERNAME . " = :username;";
        try
        {
            $query = $this->conn->prepare($sqlQuery);
            $query->bindValue(":username", $username);

            $query->execute();
            $result = $query->fetchAll();
            //print_r( $result);
            return (! empty($result));
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }

    }
    public function loginUser($username, $password)
    {
        $sqlQuery = "SELECT * FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME  . "=:username AND " . COL_USER_PASSWORD . "= :password;";
        try
        {
            if(!$this->checkIfUserExists($username))
            {
                print_r("User ne postoji");
                return false;
            }

            $query = $this->conn->prepare($sqlQuery);
            $query->bindValue(":username", $username);
            $query->bindValue(":password", crypt($password, $this->hashSalt));

            $query->execute();
            $results = $query->fetchAll();
            //print_r("Rezulat logina");
            //print_r($results);
            return (empty($results));
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }

    }
    public function getUserId($username)
    {
        $sqlQuery = " SELECT " . COL_USER_ID . " FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME . "=:username";
        try
        {
            $query = $this->conn->prepare($sqlQuery);
            $query->bindValue(":username", $username);

            $query->execute();
            return ($query->fetch());
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }

    }
    public function checkIfRunStarted($username)
    {
        $sqlQuery = "SELECT * FROM " . TBL_RUN . " WHERE " . COL_RUN_USERID . " = ( SELECT " . COL_USER_ID . " FROM " . TBL_USER . " WHERE " .  COL_USER_USERNAME . "=:username)" . " AND " . COL_RUN_ENDTIME . " IS NULL;";
        try
        {
            $query = $this->conn->prepare($sqlQuery);
            $query->bindValue(":username", $username);

            $query->execute();
            return (! empty($query->fetchAll()));
        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }
    public function insertRun($username, $startTime)
    {
//        "SELECT " . COL_USER_ID . " FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME . "=:username"
        $sqlQuery = "INSERT INTO " . TBL_RUN . " (" . COL_RUN_USERID . ", " . COL_RUN_START_TIME . ") VALUES ((" . "SELECT " . COL_USER_ID . " FROM " . TBL_USER . " WHERE " . COL_USER_USERNAME . "=:username)" .  " , :startTime);";
        try
        {
            if($this->checkIfRunStarted($username))
            {
                return false;
            }

            $query = $this->conn->prepare($sqlQuery);
            $query->bindValue(":username", $username);
            $query->bindValue("startTime", $startTime);

            $query->execute();
            return true;

        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }

    }
    public function dropRun($username)
    {
        $sqlQuery = "DELETE FROM " . TBL_RUN . " WHERE " . COL_RUN_USERID . " = ( SELECT " . COL_USER_ID . " FROM " . TBL_USER . " WHERE " .  COL_USER_USERNAME . "=:username)" . " AND " . COL_RUN_ENDTIME. " IS NULL;";
        $query = $this->conn->prepare($sqlQuery);

        $query->bindValue(":username", $username);
        $query->execute();
    }
    public function endRun($username, $endTime)
    {
        try
        {
            $sqlQuery = "UPDATE " . TBL_RUN . " SET". COL_RUN_ENDTIME ."=:endTime WHERE " . COL_RUN_USERID . " = ( SELECT " . COL_USER_ID . " FROM " . TBL_USER . " WHERE " .  COL_USER_USERNAME . "=:username)" . " AND " . COL_RUN_ENDTIME . " IS NULL;";

            $query = $this->conn->prepare($sqlQuery);

            $query->bindValue(":id", $username);
            $query->bindValue("endTime", $endTime);
            $query->execute();

        }
        catch (PDOException $e)
        {
            echo $e->getMessage();
            return false;
        }
    }

}