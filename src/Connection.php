<?php
class Connection{
    public function db_connect(){
        echo "Connecting to db";
            
        try {
            
            
            $filename="../private/db_config.ini";
            $config=parse_ini_file($filename);
            
            $host=$config['host'];
            $port=$config['port'];
            $dbname=$config['dbname'];
            $user=$config['username'];
            $password=$config['password'];
            
            # Connect to PosgreSQL Database using PDO
            $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
            $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            echo "It connected!\n";
            
            return $db;
            
        }catch(PDOException $e) {
            echo $e->getMessage();
            return null;
        }
            
            
            
        
    }
}
?>