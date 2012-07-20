<header>
    <a href="handlers/Logout.handler.php">Logout</a>    
</header>

<div id="content">
    <table>
    <tr>
        <th>Name</th>
        <th>Longitude</th>
        <th>Latitude</th>
        <th>Category</th>
        <th>Movie</th>
        <th></th>
    </tr>
    <tr>
    <?php
        $resultset = mysql_query("SELECT buildingID, b.name, longitude, latitude, c.name AS category, movieID FROM building b JOIN category c ON b.categoryID=c.categoryID ");
        
        while($data = mysql_fetch_assoc($resultset)) {
            echo '<tr>';
            echo '<td>' . $data['name'] . '</td>';     
            echo '<td>' . $data['longitude'] . '</td>';  
            echo '<td>' . $data['latitude'] . '</td>';  
            echo '<td>' . $data['category'] . '</td>';  
            echo '<td>' . $data['movieID'] . '</td>';
            echo '<td><a href="Edit?id=' . $data['buildingID'] . '">Edit</a> <a href="Delete?id=' . $data['buildingID'] . '">Delete</a></td>';
            echo '</tr>';
        }
    ?>
    </tr>
    </table>
</div>