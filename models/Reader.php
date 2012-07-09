<?php
/**
 * Base class of the Reader.
 * 
 * @package TakeALookInside/models
 * @author Sam Verschueren  <sam@irail.be>
 */
interface Reader {
    
    /**
     * Checks whether the parameters are valid.
     * 
     * @param parameters    $parameters     The url parameters
     */
    function isValid($parameters);
    
    /**
     * Executes the SQL string
     * 
     * @param sql           $sql            The sql-string that should be executed.
     */
    function execute($sql);
}
?>