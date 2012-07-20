<?php
require_once('includes/Router.php');

$router = new Router();
$router->addRoute('(?P<controller>[^/?.]*)/?(?P<action>[^.]*)', '{controller}Controller');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
    <title>Take A Look Inside - Admin</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="language" content="nl" />
    
    <link rel="icon" type="image/png" href="/images/favicon.png" />
    
    <link rel="stylesheet" type="text/css" href="/style.css" />
</head>

<body>

<?php
try {
    $view = $router->processRequest();
    
    echo $view;
}
catch(Exception $ex) {
    echo $ex->getMessage();
}
?>

</body>
</html>