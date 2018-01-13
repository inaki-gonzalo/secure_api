<?php
// This is an implementation of an authenticated extendable API
require_once 'Auth.php';
require_once 'Connection.php';
require_once 'Actions.php';

// if HTTP Request is not POST return an error
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo ("Invalid Method");
    exit();
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// define variables and set to empty values
$device_id = "";
$device_key = "";
$action = "";

// Sanity check the inputs
$device_id = test_input($_POST["device_id"]);
$device_key = test_input($_POST["device_key"]);
$action = test_input($_POST["action"]);
if ($device_id == "" || $device_key == "" || $action == "") {
    echo ("Invalid Request");
    exit();
}

// Validate credentials
$auth = new Auth();
$result = $auth->authenticate_device($device_id, $device_key);

if ($result != TRUE) {
    exit();
}

// Valid user and key
// Once user is authenticated proccess the API request

// Determine if the Action is valid
$actions = new Actions();
$APIActions = $actions->action_array;
if (! in_array($action, $APIActions)) {
    echo ("Invalid Action");
    exit();
}

// Execute the appropriate action
$actions->$action();

?>
