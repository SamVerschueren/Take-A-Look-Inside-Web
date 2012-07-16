<?php
require_once('Controller.php');
require_once('models/ReaderFactory.php');

/**
 * Buildingcontroller
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
class BuildingController extends Controller {
    
    /**
     * Getting the information of a building.
     * 
     * @param   parameters  $parameters The parameters of the url.
     */
    public function get($parameters) {
        $dataFormat = isset($_GET['dataFormat'])?$_GET['dataFormat']:'';
        $outputFormat = $parameters['format'];
        
        $reader = ReaderFactory::createReader($dataFormat);
        $printer = PrinterFactory::createPrinter($outputFormat);
        
        if(!$reader->isValid($parameters)) {
            throw new InvalidArgumentException('URL parameters are not valid');
        }
        
        $resource = 'building';
        $restrictions = parent::splitParameters($parameters['parameters']);
        
        $printer->doPrint($reader->execute($resource, $restrictions));
    }

    public function post($parameters){
        
        $connection = new Connection();
        $connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
        $connection->selectDatabase(Config::$DB);
        
        
        $buildingID=$_GET['buildingID'];
        $device=$_GET['device'];
        $method=$_GET['method'];
        $alreadylikedSQL="select count(*) as c from devices join
         must_sees on devices.deviceID = must_sees.deviceID where
          device='" . mysql_real_escape_string($device) . "' and buildingID='".mysql_real_escape_string($buildingID)."'" ;
        
        $alreadylikedResultSet = mysql_query($alreadylikedSQL);
        $alreadylikedResult=mysql_fetch_assoc($alreadylikedResultSet);
        $alreadyliked=$alreadylikedResult['c']==1;

        $deviceIDSQL="select deviceID from devices where device='". mysql_real_escape_string($device) ."'";
        $deviceIDResultSet=mysql_query($deviceIDSQL);
        $deviceIDResult= mysql_fetch_assoc($deviceIDResultSet); 
        $deviceID=$deviceIDResult['deviceID'];
        if(strlen($deviceID)>0){
            if($method=='like'){
                
                if(!$alreadyliked){
                
                    $sql="INSERT INTO must_sees (buildingID, deviceID) VALUES ('". mysql_real_escape_string($buildingID)
                    ."','". $deviceID ."' )";
                    mysql_query($sql);
                    //echo $sql;
                
                    echo "Inserted";
                }                
                else    echo "Already liked it"; 
            }else if($method=='unlike'){
                if($alreadyliked){
                    $sql="DELETE FROM must_sees WHERE buildingID='".mysql_real_escape_string($buildingID). "' and 
                    deviceID= '" . mysql_real_escape_string($deviceID) . "'"; 
                    mysql_query($sql);
                    echo "Removed";
                }
                else echo "Not yet liked? ERROR.";
            }
        }

    }
}
?>