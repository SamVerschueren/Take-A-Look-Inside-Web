<?php
session_start();

if(!isset($_SESSION['ip']) || !isset($_SESSION['user']) || !isset($_SESSION['role']) || $_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
    header('Location: Home');        
}
?>

<header>
    <a href="handlers/Logout.handler.php">Logout</a>    
</header>

<div id="content">
    <table class="center">
    <tr>
        <th>Name</th>
        <th>Category</th>
        <th>Adres</th>
        <th>Longitude</th>
        <th>Latitude</th>
        <th>Movie</th>
        <th></th>
    </tr>
    <tr>
    <?php
        $resultset = mysql_query("SELECT buildingID, adres, b.name, longitude, latitude, c.name AS category, movie
                                  FROM building b JOIN category c ON b.categoryID=c.categoryID
                                                  JOIN movie m ON b.movieID=m.movieID
                                  ORDER BY b.name");
        
        while($data = mysql_fetch_assoc($resultset)) {
            echo '<tr>';
            echo '<td>' . $data['name'] . '</td>';     
            echo '<td>' . $data['category'] . '</td>';  
            echo '<td>' . $data['adres'] . '</td>'; 
            echo '<td>' . $data['longitude'] . '</td>';  
            echo '<td>' . $data['latitude'] . '</td>';  
            echo '<td>' . $data['movie'] . '</td>';
            echo '<td><a href="Edit?id=' . $data['buildingID'] . '">Edit</a> <a href="Delete?id=' . $data['buildingID'] . '">Delete</a></td>';
            echo '</tr>';
        }
    ?>
    </tr>
    </table>
    
    <p>
        <a href="Create">Create</a><br />
        <a href="Upload">Upload movie</a>
    </p>
</div>