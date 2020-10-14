
<?php
include "checksession.php";
checkUser();
loginStatus(); 
include "header.php";
include "menu.php";
echo '<div id="site_content">';
echo '<div id="content">';
?> 
<!DOCTYPE HTML>
<html lang="en">
<head>    
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta http-equiv="X-UA-Compatible" content="ie=edge"/> 
  <title>Booking list</title>

</head>
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

$query = "SELECT * FROM `booking`ORDER BY checkin";
$result = mysqli_query($DBC, $query);
$rowcount = mysqli_num_rows($result);
/* turn off php*/
?>

<!-- button for logout-->
<form method="POST" action="login.php">
    <input type="submit" name="logout" value="Logout">   
 </form>
<?php
checkUser();
loginStatus();
?>	
<h2>Current Bookings</h2>
<h2> <a href="booking.php">[Make a Booking]</a> <a href="index.php">[Return to the Main page]</a></h2>
 
<table border="1">
<thead>
    <tr>
	<th>Booking(room,dates)</th>
    <th>Customer</th>
	<th>Review</th>
    <th>Action</th>
	</tr>
	</thead> 
 
 <?php

if ($rowcount > 0) {	

    /* fetch associative array */
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['bookingID'];
		echo '<tr><td>'.$row['roomname'].','.$row['checkin'].','.$row['checkout'].'</td>';
        echo '<td>'.$row['firstname'].','.$row['lastname'].'</td>';
		echo '<td>'.$row['review'].'</td>';
		
		echo '<td><a href="bookingview.php?id='.$id.'">[view]</a>';
		echo '<a href="editbooking.php?id='.$id.'">[edit]</a>';
		echo '<a href="editreview.php?id='.$id.'">[edit review]</a>';
		echo '<a href="deletebooking.php?id='.$id.'">[delete]</a></td>';
		echo '</tr>'.PHP_EOL;
    }
}else echo "<h2>No booking found!</h2>";
    /* free result set */
    mysqli_free_result($result);


/* close connection */
mysqli_close($DBC);


?>
</table>
 
 

 
 
</body>
</html>