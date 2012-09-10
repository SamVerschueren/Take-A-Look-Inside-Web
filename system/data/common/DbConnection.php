<?php
/**
 * Connection interface for the database
 * 
 * @package system.data.common
 * @since 2012-07-28
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class DbConnection {
    
    private $db;
    
    private $dbHost;
    private $dbUser;
    private $dbPassword;
    
    /**
     * Create a DbConnection instance.
     *
     * @param   host        $dbHost     The database host, default=localhost
     * @param   user        $dbUser     The database user, default=root
     * @param   password    $dbPassword The database password, default=<empty>
     */
    public function __construct($dbHost='localhost', $dbUser='root', $dbPassword='') {
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
    }

    /**
     * Connecting to the database.
     */
    public function connect() {
        $this->db = mysql_connect($this->dbHost, $this->dbUser, $this->dbPassword); 
        
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