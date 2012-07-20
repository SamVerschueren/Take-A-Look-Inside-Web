<?php
session_start();

require_once('Connection.php');
require_once('Config.php');

$connection = new Connection();
$connection->connect(Config::$DB_HOST, Config::$DB_USER, Config::$DB_PASSWORD);
$connection->selectDatabase(Config::$DB);

$cleanURI = str_replace(Config::$SUBDIR, '', $_SERVER['REQUEST_URI']);
$cleanURI = preg_replace('/\?.*/i', '', $cleanURI);
$cleanURI = trim($cleanURI, '/');

if(trim($cleanURI) == '') {
    $file = 'home';
}
else {
    $uri = explode('/', strtolower($cleanURI));
 
    $file = $uri[0];   
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
    <title>Take A Look Inside - Admin</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="language" content="nl" />
    
    <link rel="icon" type="image/png" href="/images/favicon.png" />
    
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>

<?php
if(!file_exists('views/' . $file . '.inc')) {
    include('views/404.inc');
}
else {
    include('views/' . $file . '.inc');
}
?>

</body>
</html>