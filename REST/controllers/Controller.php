<?php
require_once('exceptions/runtime/UnsupportedOperationException.php');
/**
 * Base class. Standard no implementation.
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
abstract class Controller {
    
    protected function devicePresentInDb($device){
        $connection = new Connection();
        $connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);        
        $connection->selectDatabase(Config::$DB);
        
        
        $query="SELECT COUNT(deviceID) AS c FROM device where device='" . mysql_real_escape_string($device) . "'";
        $resultset = mysql_query($query);
        
        if(!$resultset) {
            return false;
        }
        
        $data = mysql_fetch_assoc($resultset);
              
        return $data['c'] > 0;      
    }
    
    
    /**
     * Creating
     */
    public function post($parameters) {
        throw new UnsupportedOperationException("Posting is unsupported.");
    }
    
    /**
     * Getting
     */
    public function get($parameters) {
        throw new UnsupportedOperationException("Getting is unsupported.");
    }
    
    /**
     * Updating
     */
    public function put($parameters) {
        throw new UnsupportedOperationException("Putting is unsupported.");
    }
    
    /**
     * Deleteting
     */
    public function delete($parameters) {
        throw new UnsupportedOperationException("Deleteing is unsupported.");
    }
    
    /**
     * 
     */
    public function splitParameters($parameters) {
        $result = $_GET;
        
        $split = explode('/', $parameters);
        
        if($parameters!='') {
            for($i=0; $i<count($split); $i++) {
                $result[$split[$i]] = $split[$i+1];
           
                $i++;
            }
        }
        
        echo $result;
    }
}
?>