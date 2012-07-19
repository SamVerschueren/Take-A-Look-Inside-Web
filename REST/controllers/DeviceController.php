<?php
require_once('Controller.php');

/**
 * DeviceController
 *
 * @package TakeALookInside/controllers
 * @author Lieven Benoot  <lieven.benoot@irail.be>
 */
class DeviceController extends Controller {
    
    public function get($parameters){
        
    }
    
    public function post($parameters){
        $connection = new Connection();
        $connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
        $connection->selectDatabase(Config::$DB);
        
        $device=$_GET['device'];
        $deviceAlreadyInDbSql="select count(*) as d from device where
          device='" . mysql_real_escape_string($device) . "'";
        $deviceAlreadyInDbSqlResultSet = mysql_query($deviceAlreadyInDbSql);
        //echo $deviceAlreadyInDbSql;
        $deviceAlreadyInDbSqlResult=mysql_fetch_assoc($deviceAlreadyInDbSqlResultSet);
        $deviceAlreadyInDb=$deviceAlreadyInDbSqlResult['d']>0;
        
        if(!$deviceAlreadyInDb){
            $sqlInsert="INSERT INTO device (device) VALUES ('". mysql_real_escape_string($device)."')";
            mysql_query($sqlInsert);
            echo "Device inserted";            
        } 
        else echo "Device already present in DB";
        
    }     
}
?>