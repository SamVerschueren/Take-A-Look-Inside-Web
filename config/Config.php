<?php
/**
 * The config file.
 * Never commit this file to github or whatever.
 * 
 * @package config
 * @author Sam Verschueren  <sam.verschueren@gmail.com>
 */
class Config {
    // Database variables
    public static $DB = 'tali';
    public static $DB_HOST = 'localhost';
    public static $DB_USER = 'root';
    public static $DB_PASSWORD = '';
    
    // Fill this in if website is placed in subfolder
    public static $SUBDIR = 'tali';
    
    // The name of the session
    public static $SESSION_NAME = '';
}
?>