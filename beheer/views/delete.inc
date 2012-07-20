<?php
if(!isset($_GET['id'])) {
    header('Location: Home');
}

$resultset = mysql_query("SELECT name FROM building WHERE buildingID='" . mysql_real_escape_string($_GET['id']) . "'");
$data = mysql_fetch_assoc($resultset);
?>

<p>
<?php
if(mysql_num_rows($resultset) == 0) {
    echo 'This building does not exists. Please return to the home page.';
}    
else {
    echo '<form action="handlers/Delete.handler.php" method="post">';
    echo '<input type="hidden" name="building" value="' . $_GET['id'] . '" />';
    echo 'Are you sure you wish to delete ' . $data['name'] . '?<br />';
    echo '<input type="submit" value="Yes" />';
    echo '</form>';
}
?>
</p>