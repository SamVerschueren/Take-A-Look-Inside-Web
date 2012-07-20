<?php
/**
 * The config file.
 * Never commit this file to github or whatever.
 * 
 * @package TakeALookInside
 * @author Sam Verschueren
 */
class Config {
    // Database variables
    public static $DB = 'takealookinside';
    public static $DB_HOST = 'localhost';
    public static $DB_USER = 'root';
    public static $DB_PASSWORD = '';
    
    // Fill this in if REST api is placed in a subfolder
    public static $SUBDIR = 'beheer';
}
?>