<?php
if(isset($_SESSION['ip']) && isset($_SESSION['user']) && isset($_SESSION['role']) && $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) {
    header('Location: Admin');
}
?>

<form action="handlers/Login.handler.php" method="post">
    <fieldset>
        <legend>Login</legend>
        
        <table>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="txtUsername" /></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="txtPassword" /></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="Login" /></td>
        </tr>
        </table>
    </fieldset>
</form>