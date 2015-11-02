<?php


class DatabaseConnection{
    
    function __construct(){
	require('constants.php');
        $this->name = NULL;
        $this->database = $DB_DB;
        $this->host = $DB_HOST;
        $this->user = $DB_USER;
        $this->passwd = $DB_PASS;
    }
    
    function insertQuery($query){
        $conn = new mysqli($this->host, $this->user, $this->passwd, $this->database);
        if (!($conn->query($query))) {
	    printf("Errormessage: %s\n",$conn->error);
	}
    }
    
    function selectQuery($query) {
    	$conn = new mysqli($this->host, $this->user, $this->passwd, $this->database);
    	if (!$conn) {
    		die("Database connection failed: " . $conn->error());
    	}
    	if (!($conn->query($query))) {
	    printf("Errormessage: %s\n",$conn->error);
	}
    	$query_result = $conn->query($query) or trigger_error("Database Error: " . $conn->error);
	$results = array();
    	while ($db_result = $query_result->fetch_assoc()) {
    		$results[] = $db_result;
    	}
	$conn->close();
    	return $results;
    }
    
    function confirm_query($query_set){
        if (!$query_set){
            die("Database query failed: " . mysql_error());
        }
    }
    
    function getColumn($table, $column){
	$database = new DatabaseConnection();
	$results = $database -> selectQuery("SELECT {$column} FROM {$table};");
	foreach ($results as $result) {
	    $resultArray[] = $result[$column];
	}
	
	return $resultArray;
	}
}

function imageToBinary($image) {
	$fp = fopen($image, 'r');
	$imageData = fread($fp, filesize($image));
	$imageData = addslashes($imageData);
	fclose($fp);
	return $imageData;
}

function arrayToRadioInputs($name, $valueArray, $prefix='', $suffix=''){
    $i = 1;
    foreach ($valueArray as $value){
        echo $prefix;
        echo "<input id='{$name}{$i}' name='{$name}' value='{$value}' type='radio'";
        if ($i == 1){
            echo " checked ";
        }
        echo ">{$value}";
        $i++;
        echo $suffix;
    }
}

function arrayToSelectInputs($name, $valueArray, $default=null){
    echo "<select id={$name} name={$name}>";
    foreach ($valueArray as $value){
        echo "<option value='{$value}' ";
	if ($value == $default) {
	    echo "selected ";
	}
	echo ">{$value}</option>";
        echo "{$value}";
    }
    echo "</select>";
}




?>