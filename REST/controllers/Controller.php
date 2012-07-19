<?php
require_once('exceptions/runtime/UnsupportedOperationException.php');
/**
 * Base class for implementation of the RESTfull implementation.
 * Provides different methods of restfull interface and some abstract methods.
 *
 * @package TakeALookInside/controllers
 * @author Sam Verschueren  <sam@irail.be>
 */
abstract class Controller {
    /**
     * Checks if a given devicename is found in the database.
     * 
     * @param   $device     devicename of the device to search for.
     * @return  boolean     $data['c']>0    returns true if device was found in the Database.
     */
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
     * Methods that can be overridden by subclasses. Methods are not supported if subclass does not implement them.
     * 
     */
    
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
     * Parses all parameters from the URL in an array of different parameters based on the '/' delimiter.
     * 
     * @param $parameters   All parameters from the URL
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
        
        return $result;
    }
}
?>