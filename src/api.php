<?php
//This is an implementation of an authenticated extendable API
require_once 'Auth.php';
require_once 'Connection.php';

// define variables and set to empty values
$device_id = "";
$device_key = "";
$action = "";

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

#if HTTP Request is not POST return an error
if ($_SERVER["REQUEST_METHOD"] != "POST") {
	 echo("Invalid Method");
	 exit;
}

#Sanity check the inputs
$device_id = test_input($_POST["device_id"]);
$device_key = test_input($_POST["device_key"]);
$action = test_input($_POST["action"]);
if($device_id=="" || $device_key == "" || $action==""){
	echo("Invalid Request");
	exit;

}


//Validate credentials
$auth=new Auth();
$result=$auth->authenticate_device($device_id, $device_key);

if($result != TRUE){
    exit;
}


//Valid user and key
//Once user is authenticated proccess the API request

//Determine if the Action is valid
$APIActions = array("GetTime","InsertData");
if (!in_array($action, $APIActions)) {
	echo("Invalid Action");
	exit;
}

//Proccess getTime action
//Return seconds since epoch 1/1/1970
if($action == "GetTime"){
	$now = new DateTime();
	echo $now->getTimestamp();   
	exit;
}

if($action == "InsertData"){
    
	$now = new DateTime();
	$server_time=$now->getTimestamp(); 
	
	$data_value = test_input($_POST["data_value"]);
	$sensor_id = test_input($_POST["sensor_id"]);
	$device_time = test_input($_POST["device_time"]);
	if($data_value==""){
		echo("Data empty!\n");
		exit;
	}
	if($sensor_id ==""){
	    echo("No sensor id!\n");
	    exit;
	}	
	if($device_time ==""){
	    echo("No device time!\n");
	    exit;
	}	
	
	$conn=new Connection();
	$db = $conn->db_connect();
	if($db == null){ //check connection was successful
	    echo("database not available\n");
	    exit;
	}
	
	# the data we want to insert
	$data = array( 'data_sensor_id' => $sensor_id,
	    'data_value' => $data_value ,
	    'data_device_time' => $device_time ,
	    'data_server_time' => $server_time);
	
	try{
	$STH = $db->prepare("INSERT INTO data (data_sensor_id, data_value, data_device_time,data_server_time) 
                         values (:data_sensor_id, :data_value, 
                                  to_timestamp(:data_device_time), to_timestamp(:data_server_time))");
	$rc=$STH->execute($data);
	
	}catch(PDOException $e) {
	    echo $e->getMessage();
	    exit;
	}
	//Clean up database
	$STH=null;
	$db=null;
	
	if($rc == FALSE ){
	    echo 'API: Failed to insert';
	    exit;
	}
	echo ("Inserted data:".$data_value." at:".$now->getTimestamp());
	exit;
}


?>

