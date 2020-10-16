
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
 <title>View Booking</title> </head>
 <body>

<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}

//do some simple validation to check if id exists
$id = $_GET['id'];
if (empty($id) or !is_numeric($id)) {
 echo "<h2>Invalid BookingID</h2>"; //simple error feedback
 exit;
} 

//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT * FROM booking WHERE bookingID='.$id;
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result); 
?>

<!-- button for logout-->
<form method="POST" action="login.php">
    <input type="submit" name="logout" value="Logout">   
 </form>
<?php
checkUser();
loginStatus();
?>	
<h1>Booking Details View</h1>
<h2><a href='currentbookinglist.php'>[Return to the Booking listing]</a><a href='index.php'>[Return to the main page]</a></h2>
<?php

//makes sure we have the Room
if ($rowcount > 0) {  
   echo "<fieldset><legend>Booking detail #$id</legend><dl>"; 
   $row = mysqli_fetch_assoc($result);
   echo "<dt>Room name:</dt><dd>".$row['roomname']."</dd>".PHP_EOL;
    echo "<dt>Name:</dt><dd>".$row["firstname"]."</dd>".PHP_EOL;
		echo "<dt>Lastname:</dt><dd>".$row["lastname"]."</dd>".PHP_EOL;
		echo "<dt>Phone:</dt><dd>".$row["phone"]."</dd>".PHP_EOL;
		 echo "<dt>Extras:</dt><dd>".$row["extras"]."</dd>".PHP_EOL;
   
         echo "<dt>Review:</dt><dd>".$row['review']."</dd>".PHP_EOL;
   echo '</dl></fieldset>'.PHP_EOL;  
} else echo "<h2>No Booking found!</h2>"; //suitable feedback

mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
?>
</table>
</body>
</html>
  
