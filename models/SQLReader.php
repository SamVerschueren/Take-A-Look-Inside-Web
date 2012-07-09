<?php
require_once('Reader.php');

class SQLReader implements Reader {
    
    public function isValid($parameters) {
        return true;
    }
    
    public function read($parameters) {
        $table = $parameters['resource'];
        $where = '';
        
        $parameterSplit = explode('/', $parameters['parameters']);
        
        if(count($parameterSplit)%2 != 0) {
            throw new InvalidArgumentException('tet');
        }
        
        $parameterList = array();
        for($i=0; $i<count($parameterSplit); $i++) {
            $_GET[$parameterSplit[$i]] = $parameterSplit[$i+1];
            
            $i++;
        }
        
        foreach($_GET as $key => $value) {
            if($key != 'dataFormat') {
                if(empty($where)) {
                    $where .= ' WHERE ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';
                }
                else {
                    $where .= ' AND ' . mysql_real_escape_string($key) . '=\'' . mysql_real_escape_string($value) . '\'';
                }
            }
        }
        
        echo 'SELECT * FROM ' . mysql_real_escape_string($table) . $where;
    }
}
?>