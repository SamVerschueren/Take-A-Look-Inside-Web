<?php
session_start();

if(!isset($_SESSION['ip']) || !isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    header('Location: ../Home');        
}

require_once('../Connection.php');
require_once('../Config.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $connection = new Connection();
    $connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
    $connection->selectDatabase(Config::$DB);
    
    $userName = $_POST['txtUsername'];
    $password = strtoupper(sha1($_POST['txtPassword']));
    
    $query = mysql_query("SELECT userId, userRole FROM user WHERE userName='" . mysql_real_escape_string($userName) . "' AND userPassword='" . mysql_real_escape_string($password) . "'") or die(mysql_error());
    
    if($query === false) {
        echo 'Query could not be executed';
    }
    else {    
        if(mysql_num_rows($query) > 0) {
            $data = mysql_fetch_assoc($query);
            
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['user'] = $data['userId'];
            $_SESSION['role'] = $data['userRole'];
        }    
    }    
}

header('Location: ../Home');
?>