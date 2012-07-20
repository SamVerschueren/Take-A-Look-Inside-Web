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

$target_path = "../../mov/";
$fileName = basename($_FILES['txtFile']['name']);
$target_path = $target_path . $fileName;

$extension = substr($fileName, strlen($fileName)-3);

if($extension != '3gp') {
    echo 'The extension is not correct. You can only upload a .3gp movie.';   
}
else {
    if(move_uploaded_file($_FILES['txtFile']['tmp_name'], $target_path)) {
        $token = base64_encode(date('YmdHi') . $fileName);
        
        mysql_query("INSERT INTO movie(movie, dateTime, qrID) VALUES('" . $fileName . "', NOW(), '" . $token . "')");
        
        header('Location: ../Home');
    } else{
        echo 'There was an error uploading the file, please try again!';
    }    
}
?>