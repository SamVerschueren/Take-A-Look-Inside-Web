<?php
session_start();

if(!isset($_SESSION['ip']) || !isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    header('Location: ../Home');        
}

require_once('../Connection.php');
require_once('../Config.php');

$connection = new Connection();
$connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
$connection->selectDatabase(Config::$DB);

$buildingID     =   mysql_real_escape_string($_POST['txtBuilding']);
$name           =   mysql_real_escape_string($_POST['txtName']);
$category       =   mysql_real_escape_string($_POST['txtCategory']);
$adres          =   mysql_real_escape_string($_POST['txtAdres']);
$longitude      =   mysql_real_escape_string($_POST['txtLongitude']);
$latitude       =   mysql_real_escape_string($_POST['txtLatitude']);
$movie          =   mysql_real_escape_string($_POST['txtMovie']);
$description    =   mysql_real_escape_string($_POST['txtDescription']);

mysql_query("UPDATE building SET name='" . $name . "', description='" . $description . "', 
                                                       longitude='" . $longitude . "', 
                                                       latitude='" . $latitude . "', 
                                                       adres='" . $adres . "',
                                                       movieID='" . $movie . "',
                                                       categoryID='" . $category . "'
             WHERE buildingID='" . $buildingID . "'");
             
header('Location: ../Home');
?>