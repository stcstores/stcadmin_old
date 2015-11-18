<?php
namespace LSPHP;

class DatabaseConnection {

    public function __construct($host, $database, $user, $passwd)
    {
        $this->host = $host;
        $this->database = $database;
        $this->user = $user;
        $this->passwd = $passwd;
    }

    public function insertQuery($query)
    {
        $conn = new \mysqli($this->host, $this->user, $this->passwd, $this->database);
        if (!($conn->query($query))) {
            trigger_error("Errormessage: ". $conn->error, E_USER_NOTICE);
            die();
        }
        $conn->close();
    }

    public function selectQuery($query)
    {
        $conn = new \mysqli($this->host, $this->user, $this->passwd, $this->database);
        if (!$conn) {
            die("Database connection failed: " . $conn->error());
        }
        if (!($conn->query($query))) {
            trigger_error("Errormessage: ". $conn->error, E_USER_NOTICE);
            die();
        }
        $query_result = $conn->query($query) or trigger_error("Database Error: " . $conn->error);
        $results = array();
        while ($db_result = $query_result->fetch_assoc()) {
            $results[] = $db_result;
        }
        $conn->close();
        return $results;
    }

    public function confirmQuery($query_set)
    {
        if (!$query_set) {
            die("Database query failed: " . mysql_error());
        }
    }

    public function getColumn($table, $column)
    {
        $results = $this -> selectQuery("SELECT {$column} FROM {$table};");
        foreach ($results as $result) {
            $resultArray[] = $result[$column];
        }
        return $resultArray;
    }
}
