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
     * @return  void
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
    /**
     * Post method that is able of inserting must sees in the database.
     * Checks if the building is already liked by the specific device.
     * If the building is already liked by the given device, the method = 'unlike',
     * if not, then the method = 'like'
     * Depending on the method, the given device & building couple are inserted or
     * removed from the must_see table.
     * 
     * @param  array   $parameters The parameters of the url.
     * @return void 
     * 
     */    
    public function post($parameters){
        //DB connection
        $connection = new Connection();
        $connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
        $connection->selectDatabase(Config::$DB);
        
        //Getting parameters from URL
        $buildingID=$_POST['buildingID'];
        $device=$_POST['device'];
        $method=$_POST['method'];
        
        //SQL that counts the likes from a specific device for a specific location.        
        $alreadylikedSQL="select count(*) as c from device join must_sees on device.deviceID = must_sees.deviceID where device='" . mysql_real_escape_string($device) . "' and buildingID='".mysql_real_escape_string($buildingID)."'" ;
        $alreadylikedResultSet = mysql_query($alreadylikedSQL);
        $alreadylikedResult=mysql_fetch_assoc($alreadylikedResultSet);
        //reading 'c' column from first record (count value)
        //if the count = 1, the device already liked that location.
        $alreadyliked=$alreadylikedResult['c']==1;
        
        //Get devicename corresponding to given deviceID in URL.        
        $deviceIDSQL="select deviceID from device where device='". mysql_real_escape_string($device) ."'";
        $deviceIDResultSet=mysql_query($deviceIDSQL);
        $deviceIDResult= mysql_fetch_assoc($deviceIDResultSet); 
        $deviceID=$deviceIDResult['deviceID'];
        
        if(strlen($deviceID)>0){
            if($method=='like'){
                //Not liked yet--> like: Insert                
                if(!$alreadyliked){                
                    $sql="INSERT INTO must_sees (buildingID, deviceID) VALUES ('". mysql_real_escape_string($buildingID)
                    ."','". $deviceID ."' )";
                    mysql_query($sql);
                    echo "Inserted";
                }                
                else // Should not happen: building is already liked, but method is like/
                    echo "Already liked it"; 
            }else if($method=='unlike'){
                //Already liked--> unlike: Remove
                if($alreadyliked){
                    $sql="DELETE FROM must_sees WHERE buildingID='".mysql_real_escape_string($buildingID). "' and 
                    deviceID= '" . mysql_real_escape_string($deviceID) . "'"; 
                    mysql_query($sql);
                    echo "Removed";
                }
                else // Should not happen: building is not liked but method is unlike
                    echo "Not yet liked? But method = unlike --> ERROR.";
            }
        }

    }
}
?>