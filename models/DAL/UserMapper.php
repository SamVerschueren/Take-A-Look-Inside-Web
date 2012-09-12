<?php
require_once('models/domain/User.php');

require_once('Mapper.php');

/**
 * This class maps every SQL user to a PHP user.
 * 
 * @package models.DAL
 * @since 2012-09-07
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class UserMapper extends Mapper {
    
    /**
     * Returns a Collection of all objects for the given mapper.
     * 
     * @return  objects     Array of all the objects.
     * @throws  exception   UnsupportedOperationException if method is not overriden
     */
    public function findAllObjects() {
        $resultset = mysql_query("SELECT id, userName, userRole FROM user");
        
        if(!$resultset) {
            throw new SQLException('Error while retrieving the users.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('No users where found.');
        }
        
        $result = array();
        
        while($data = mysql_fetch_assoc($resultset)) {
            $user = new User($data['userName'], $data['userRole'], '');
            $user->setId($data['id']);
        }
        
        return $result;
    }
    
    public function findByNameAndPassword($name, $password) {
        $password = strtoupper(sha1($password));
        
        $resultset = mysql_query("SELECT id, userName, userRole FROM user WHERE userName='" . mysql_real_escape_string($name) . "' AND userPassword='" . mysql_real_escape_string($password) . "'");
    
        if(!$resultset) {
            throw new SQLException('An error occured when you tried to login. Please try again later.');
        }
        
        if(mysql_num_rows($resultset) == 0) {
            throw new SQLException('The username or password you provided is incorrect.');
        }
        
        $data = mysql_fetch_assoc($resultset);
        
        $user = new User($data['userName'], $data['userRole']);
        $user->setId($data['id']);
        
        return $user;
    }
}
?>