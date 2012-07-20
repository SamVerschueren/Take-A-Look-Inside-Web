<?php
if(!isset($_SESSION['ip']) || !isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    header('Location: Home');        
}

if($file == 'edit') {
    $buildingID = $_GET['id'];

    $resultset = mysql_query("SELECT * FROM building WHERE buildingID='" . mysql_real_escape_string($buildingID) . "'");
    $data = mysql_fetch_assoc($resultset);
    
    $title          =   $data['name'];
    
    $buildingID     =   $data['buildingID'];
    $name           =   $data['name'];
    $adres          =   $data['adres'];
    $longitude      =   $data['longitude'];
    $latitude       =   $data['latitude'];
    $description    =   $data['description'];   
}
else if($file == 'create') {
    $title = 'New building';
    
    $buildingID     =   '';
    $name           =   '';
    $adres          =   '';
    $longitude      =   '';
    $latitude       =   '';
    $description    =   '';     
}
?>

<h1><?php echo $title; ?></h1>

<form action="handlers/Edit.handler.php" method="post">
    <input type="hidden" name="caller" value="<?php echo $file; ?>" />
    <input type="hidden" name="txtBuilding" value="<?php echo $buildingID ?>" />
    
    <table align="center">
    <tr>
        <td>Name:</td>
        <td><input type="text" name="txtName" value="<?php echo $name; ?>" /></td>
    </tr>
    <tr>
        <td>Category:</td>
        <td>
            <select name="txtCategory">
                <?php
                    $categoryResultset = mysql_query("SELECT * FROM category");
                    
                    while($category = mysql_fetch_assoc($categoryResultset)) {
                        $selected='';
                        if($file=='edit' && $category['categoryID'] == $data['categoryID']) {
                            $selected = ' selected="selected"';
                        }
                        
                        echo '<option value="' . $category['categoryID'] . '"' . $selected . '>' . $category['name'] . '</option>';
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Adres:</td>
        <td><input type="text" name="txtAdres" value="<?php echo $adres; ?>" /></td>
    </tr>
    <tr>
        <td>Longitude:</td>
        <td><input type="text" name="txtLongitude" value="<?php echo $longitude; ?>" /> <a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm">Find</a></td>
    </tr>
    <tr>
        <td>Latitude:</td>
        <td><input type="text" name="txtLatitude" value="<?php echo $latitude; ?>" /></td>
    </tr>
    <tr>
        <td>Movie:</td>
        <td>
            <select name="txtMovie">
                <?php
                    $movieResultset = mysql_query("SELECT * FROM movie");
                    
                    while($movie = mysql_fetch_assoc($movieResultset)) {
                        $selected='';
                        if($file=='edit' && $movie['movieID'] == $data['movieID']) {
                            $selected = ' selected="selected"';
                        }
                        
                        echo '<option value="' . $movie['movieID'] . '"' . $selected . '>' . $movie['movie'] . '</option>';
                    }
                ?>
            </select>   
        </td>
    </tr>
    <tr>
        <td>Description:</td>
        <td><textarea name="txtDescription" rows="5" cols="30"><?php echo $description; ?></textarea></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" value="Save" /></td>
    </tr>
    </table>
</form>