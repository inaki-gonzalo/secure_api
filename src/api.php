<?php
//This is an implementation of an authenticated extendable API
require_once 'Auth.php';
require_once 'Connection.php';

// define variables and set to empty values
$user_id = "";
$user_key = "";
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
$user_id = test_input($_POST["user_id"]);
$user_key = test_input($_POST["user_key"]);
$action = test_input($_POST["action"]);
if($user_id=="" || $user_key == "" || $action==""){
	echo("Invalid Request");
	exit;

}


//Validate credentials
$auth=new Auth();
$result=$auth->authenticate_regular_user($user_id, $user_key);

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


?>

