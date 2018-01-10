<?php
class Actions{
    var $action_array=array("GetTime","InsertData");
    public function GetTime(){
        $now = new DateTime();
        echo $now->getTimestamp();   
        return TRUE;
        
    }
    
    public function InsertData(){
        $now = new DateTime();
        $server_time=$now->getTimestamp();
        
        $data_value = test_input($_POST["data_value"]);
        $sensor_id = test_input($_POST["sensor_id"]);
        $device_time = test_input($_POST["device_time"]);
        if($data_value==""){
            echo("Data empty!\n");
            return FALSE;
            #exit;
        }
        if($sensor_id ==""){
            echo("No sensor id!\n");
            return FALSE;
            #exit;
        }
        if($device_time ==""){
            echo("No device time!\n");
            return FALSE;
            #exit;
        }
        
        $conn=new Connection();
        $db = $conn->db_connect();
        if($db == null){ //check connection was successful
            echo("database not available\n");
            return FALSE;
            #exit;
        }
        
        # the data we want to insert
        $data = array( 'data_sensor_id' => $sensor_id,
            'data_value' => $data_value ,
            'data_device_time' => $device_time ,
            'data_server_time' => $server_time);
        
        try{
            $STH = $db->prepare("INSERT INTO data_real (data_sensor_id, data_value, data_device_time,data_server_time)
                         values (:data_sensor_id, :data_value,
                                  to_timestamp(:data_device_time), to_timestamp(:data_server_time))");
            $rc=$STH->execute($data);
            
        }catch(PDOException $e) {
            echo $e->getMessage();
            return FALSE;
            #exit;
        }
        //Clean up database
        $STH=null;
        $db=null;
        
        if($rc == FALSE ){
            echo 'API: Failed to insert';
            return FALSE;
            #exit;
        }
        echo ("Inserted data:".$data_value." at:".$now->getTimestamp());
        return TRUE;
        #exit;
    }
    
}


?>