<!DOCTYPE HTML>
<html><head><title>Room search</title> </head>
 <body>

 <?php

include "config.php"; //load in any variables

// Create connection
$DBC = mysqli_connect(DBHOST, DBUSER , DBPASSWORD, DBDATABASE);
// Check connection


if ($DBC -> connect_errno) {
  echo "Failed to connect to MySQL: " . $DBC -> connect_error;
  exit();
}
        	/* retrieve a row from the results
	   one at a time until no rows left in the result */


  $query ="SELECT * FROM `room` WHERE roomname NOT IN(SELECT roomname FROM `booking` WHERE checkin >=checkin AND checkout <=checkout )"; 

   $result = mysqli_query($DBC,$query);
	 // $rowcount = mysqli_num_rows($result); 
?>

<table border="1">
<thead>
    <tr>
	<th>Room name</th>
    <th>Room Type</th>
	<th>Beds</th>
	</tr>
	</thead>

  <?php   
	if ($result) { 
		  /* fetch associative array */
        while ($row = mysqli_fetch_assoc($result)) {
         $id = $row['roomID'];
        echo '<tr><td>'.$row['roomname'].'</td>';
		echo '<td>'.$row['roomtype'].'</td>'; 
        echo '<td>'.$row['beds'].'</td>'.PHP_EOL; 
     echo '</tr>'.PHP_EOL;
	  }
} else echo "<h2>No rooms found!</h2>"; //suitable feedback
echo "</table>";

/* close connection */
mysqli_close($DBC);

?>

</body>
</html>



