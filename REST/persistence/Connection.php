<?php
/**
 * Connection interface for the database
 * 
 * @package TakeALookInside/persistence
 * @author Sam Verschueren  <sam@irail.be>
 */
class Connection {
	
	private $db;
	
    /**
     * Connecting to the database.
     *
     * @param   host        $dbHost     The database host, default=localhost
     * @param   user        $dbUser     The database user, default=root
     * @param   password    $dbPassword The database password, default=<empty>
     */
    public function connect($dbHost='localhost', $dbUser='root', $dbPassword='') {
        $this->db = mysql_connect($dbHost, $dbUser, $dbPassword); 
        
        if(!$this->db) {
            throw new SQLException('Can not connect with MySQL database');
        }
    }
    
    /**
     * Selecting the database.
     *
     * @param   database    $database   The database that should be selected
     */
    public function selectDatabase($database) {
        mysql_select_db($database, $this->db);
    }
    
    /**
     * Get the connection
     *
     * @return  database    $db         The database
     */
	public function getConnection() {
		return $this->db;
	}
}
?>