<?php
/**
 * Database connection
 * @author Sam Verschueren
 */
class Connectie {
	
    const DATABASE_NAME = "db-name";
    const DATABASE_HOST = "localhost";
    const DATABASE_USER = "root";
    const DATABASE_PASSWORD = "";
    
    private $db;
    
    public function connect() {
        $this->db = mysql_connect(self::DATABASE_HOST, self::DATABASE_USER, self::DATABASE_PASSWORD);
        
        if(!$this->db) {
        	throw new Exception("Kan geen verbinding maken met de database.");	
        }
        
        mysql_select_db(self::DATABASE_NAME, $this->db);
    }
    
    public function getConnection() {
        return $this->db;
    }
}
?>