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
     * Returns true if valid, throws MalformedURLException if not valid.
     * 
     * @param   parameters      $parameters     The url parameters
     * 
     * @return  boolean         return true of valid.
     */
    public function isValid($parameters) {
        //split parameters
        $parameterSplit = trim($parameters['parameters'])==''?array():explode('/', $parameters['parameters']);
        
        //parametercount should be multiple of 2
        if(count($parameterSplit)%2 != 0) {
            //error
            throw new MalformedURLException('Odd number of parameters specified.');
        }        
        //OK
        return true;
    }
    
    /**
     * 
     * Connects to the database and executes an SQL string,
     * filters a specific dataset ($resource) based on different filters ($restrictions).
     * 
     * 
     * @param   sql     $sql            The sql-string that should be executed.
     * 
     * @return  array   returns the resultset as an array.
     */
    public function execute($resource, $restrictions) {
        // Connect to the database
        $connection = new Connection();
        $connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
        $connection->selectDatabase(Config::$DB);
        
        //declare variables
        $where = '';       
        $orderby='';
        $select='';
        $jointable='';
        
        //check if the restrictions contain a 'top' restriction
        //Hardcoded single purpose implementation for this case in order to keep it simple because then it's not necessary
        //to integrate it in the normal SQL reading process.
        //
        //Is a single purpose solution in order to get the Must See list to view on the hompage.                
        if(array_key_exists("top" , $restrictions)){
            //Build query string
            //Selects the buildings which are favorited the most by all the different users.
            //counts the # of devices int he must_see table, grouped on the buildings.
            //orders by that count 
            // --> returns resultset of buildings with most "Must See's"
            $sql="select building.name, building.buildingID, building.adres, category.name AS catName, count(*) AS mustSee from building join must_sees on building.buildingID = must_sees.buildingID join category on building.categoryID=category.categoryID
            group by name, buildingID, adres, catName order by count(*) desc limit " . mysql_real_escape_string($restrictions["top"]);     
        }
        //All other cases: normal process, each filter (restriction) is handled separately
        else{
            //Loop through all restricitons        
            foreach($restrictions as $key => $value)    
                //Ignore dataFormat, this is no restriction, but the format to determine which printer that should be used             
                if($key != 'dataFormat') 
                
                    //Order by clause: Ascending, sorts ascending based on given columnname value
                    if(strtolower($key) == 'orderby')
                        $orderby = " order by " .$value;
                        
                    //Order by clause: Descending, sorts descending based on given columnname value     
                    else if(strtolower($key) == 'orderbydesc')
                        $orderby = " order by " .$value . " desc";
                        
                    //Select clause: contains all colums that should be selected.                     
                    else if(strtolower($key)=='select')
                        $select=str_replace(";", ",", $value); 
                      
                    //Join clause: sets the jointable value, can only join on 1 table.
                    //$jointable is later on used to create join syntax
                    else if(strtolower($key)=='join')        
                        $jointable=$value;    
                    
                    //Where clause: add where clause, based on $key and $value.          
                    else if($where == '')                
                        $where = 'WHERE ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';     
                    else 
                    //Where clause AND: adds other where clauses like above.
                        $where .= ' AND ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';
                        
            //Build SQL string witch selects.
            $sql="select " . ( strlen($select)>0? $select: "*"). " from " .$resource;
            
            //Check if different clauses are present and add them to the query string.
            
            if(strlen($jointable)>0)
                $sql .= " join ".$jointable." on ". $resource. ".".$jointable."ID=".$jointable.".".$jointable."ID";
     
            if(strlen($where)>0)
                $sql .= " ".$where;
           
            if(strlen($orderby)>0)
                $sql .= $orderby;
        }

        //Testing purpose template:
        //echo $sql;        
        
        $resultset = mysql_query($sql) ;
        
        if(!$resultset) {
            throw new SQLException('SQL could not be executed.');
        }
        //Return resultset as array.
        return $this->resultsetToArray($resource, $resultset);
    }

    /**
     * Converting a resultset to an array.
     * 
     * @param resultset $resultset  The resultset that should be converted.
     */
    private function resultsetToArray($resource, $resultset) {
        $rows = array();
        //fetch each row
        while($r = mysql_fetch_assoc($resultset)) {
            $rows[$resource][] = $r;
        }
        //return array
        return $rows;
    }
}
?>