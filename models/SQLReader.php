<?php
require_once('Reader.php');
require_once('persistence/Connection.php');
require_once('Config.php');
require_once('exceptions/MalformedURLException.php');
require_once('exceptions/SQLException.php');

/**
 * Reader that can read from SQL datasource
 * 
 * @package TakeALookInside/models
 * @author Sam Verschueren  <sam@irail.be>
 */
class SQLReader implements Reader {
    
    /**
     * Checks whether the parameters are valid.
     * 
     * @param parameters    $parameters     The url parameters
     */
    public function isValid($parameters) {
        $parameterSplit = trim($parameters['parameters'])==''?array():explode('/', $parameters['parameters']);
        
        if(count($parameterSplit)%2 != 0) {
            throw new MalformedURLException('Odd number of parameters specified.');
        }
        
        return true;
    }
    
    /**
     * Executes the SQL string
     * 
     * @param sql           $sql            The sql-string that should be executed.
     */
    public function execute($resource, $restrictions) {
        // Connect to the database
        $connection = new Connection();
        $connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
        $connection->selectDatabase(Config::$DB);
        
        $where = '';
        
        foreach($restrictions as $key => $value) {
            if($key != 'dataFormat') {
                if($where == '') {
                    $where = 'WHERE ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';
                }
                else {
                    $where .= ' AND ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';
                }
            }
        }
        
        $sql = 'SELECT * FROM ' . mysql_real_escape_string($resource) . ' ';
        $sql .= $where;
        
        $resultset = mysql_query($sql);
        
        if(!$resultset) {
            throw new SQLException('SQL could not be executed.');
        }
        
        return $this->resultsetToArray($resource, $resultset);
    }

    /**
     * Converting a resultset to an array.
     * 
     * @param resultset $resultset  The resultset that should be converted.
     */
    private function resultsetToArray($resource, $resultset) {
        $rows = array();
        while($r = mysql_fetch_assoc($resultset)) {
            $rows[$resource][] = $r;
        }
        
        return $rows;
    }
}
?>