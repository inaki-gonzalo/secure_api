<?php

require_once 'Connection.php';
class Auth{
    
    public function authenticate_device($device_id,$device_key){
        $conn=new Connection();
        $db = $conn->db_connect();
        if($db == null){ //check connection was successful
            return False;
        }
        # the data we want to insert
        $data = array( 'device_id' => $device_id);
        
        # the shortcut!
        $STH = $db->prepare("SELECT device_key_hash FROM devices where device_id = :device_id");
        $rc=$STH->execute($data);
        
        if($rc == FALSE ){
            echo 'Auth:device query failed';
            return FALSE;
        }
        
        $response = $STH->fetch(PDO::FETCH_ASSOC);
        $device_key_hash=$response['device_key_hash'];
        
        $STH=null;
        $db=null;
        
        if (!password_verify($device_key, $device_key_hash)) {
            echo 'Invalid key';
            return FALSE;
        }
        
        return TRUE;
    }
    
    
    //Validate credentials
    //use name to find hashed version of key.
    //Hash key and secure compare.
    //password_verify is secure against timing attacks.
    public function authenticate_regular_user($user_id,$user_key){
        $conn=new Connection();
        $db = $conn->db_connect();
        if($db == null){ //check connection was successful
            return False;
        }
        # the data we want to insert
        $data = array( 'user_id' => $user_id);
        
        # the shortcut!
        $STH = $db->prepare("SELECT user_key_hash FROM USERS where user_id = :user_id");      
        $rc=$STH->execute($data);
        
        if($rc == FALSE ){
            echo 'Auth:user query failed';
            return FALSE;
        }
        
        $response = $STH->fetch(PDO::FETCH_ASSOC);
        $user_key_hash=$response['user_key_hash'];
        
        $STH=null;
        $db=null;
        
        if (!password_verify($user_key, $user_key_hash)) {
            echo 'Invalid key';
            return FALSE;
        }
       
        return TRUE;
    }
    public function authenticate_admin_user($user_id,$user_key){
        
        $conn=new Connection();
        $db = $conn->db_connect();
        if($db == null){ //check connection was successful
            return False;
        }
        # the data we want to insert
        $data = array( 'user_id' => $user_id);
        
        # the shortcut!
        $STH = $db->prepare("SELECT user_key_hash,user_group FROM USERS where user_id = :user_id");
        $rc=$STH->execute($data);
        
        if($rc == FALSE ){
            echo 'Auth:user query failed';
            return FALSE;
        }
        
        $response = $STH->fetch(PDO::FETCH_ASSOC);
        $user_key_hash=$response['user_key_hash'];
        $user_group=$response['user_group'];
        
        $STH=null;
        $db=null;
        
        if (!password_verify($user_key, $user_key_hash)) {
            echo 'Invalid key';
            return FALSE;
        }
        if($user_group != "admin"){
            return 'Invalid Group';
            return FALSE;
        }
        
        
        return TRUE;
    }
    
}
    
?>