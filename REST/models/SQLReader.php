<?php
require_once('IReader.php');
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
class SQLReader implements IReader {
    
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
        $orderby='';
        $select='';
        $jointable='';
        if(array_key_exists("top" , $restrictions)){
           $sql="select building.name, building.buildingID, building.adres, category.name AS catName, count(*) AS mustSee from building join must_sees on building.buildingID = must_sees.buildingID join category on building.categoryID=category.categoryID
            group by name, buildingID, adres, catName order by count(*) desc limit " . mysql_real_escape_string($restrictions["top"]);     
        }else{        
            foreach($restrictions as $key => $value)                 
                if($key != 'dataFormat') 
                    if(strtolower($key) == 'orderby')
                        $orderby = " order by " .$value;     
                    else if(strtolower($key) == 'orderbydesc')
                        $orderby = " order by " .$value . " desc";                     
                    else if(strtolower($key)=='select')
                        $select=str_replace(";", ",", $value);   
                    else if(strtolower($key)=='join')        
                        $jointable=$value;    
                    else if(strtolower($key)=='callback') { }
                        // do nothing            
                    else if($where == '')                
                        $where = 'WHERE ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';     
                    else 
                        $where .= ' AND ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';
            $sql="select " . ( strlen($select)>0? $select: "*"). " from " .$resource;
            if(strlen($jointable)>0)
                $sql .= " join ".$jointable." on ". $resource. ".".$jointable."ID=".$jointable.".".$jointable."ID";
            if(strlen($where)>0)
                $sql .= " ".$where;
            if(strlen($orderby)>0)
                $sql .= $orderby;
        }
        //echo $sql;
        $resultset = mysql_query($sql) ;
        
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