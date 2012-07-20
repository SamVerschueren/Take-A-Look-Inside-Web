<?php
if(!isset($_SESSION['ip']) || !isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    header('Location: Home');        
}
?>

<h1>Upload movie</h1>

<form enctype="multipart/form-data" action="handlers/Upload.handler.php" method="post">
    <table align="center">
    <tr>
        <td>File:</td>
        <td><input type="file" name="txtFile" /></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" value="Upload" /></td>
    </tr>
    </table>
</form>