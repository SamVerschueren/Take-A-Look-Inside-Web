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

$building = $_POST['building'];

mysql_query("DELETE FROM building WHERE buildingID='" . mysql_real_escape_string($building) . "'");

header('Location: ../Home');
?>