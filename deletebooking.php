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
<html><head><title>Delete booking</title> </head>
<body>

<?php
include "config.php"; //load in any variables

// Create connection
$DBC = mysqli_connect(DBHOST, DBUSER , DBPASSWORD, DBDATABASE);
// Check connection
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}
//this line is for debugging purposes so that we can see the actual POST/GET data
//echo "<pre>"; var_dump($_POST); var_dump($_GET);echo "</pre>";

//function to clean input but not validate type and content
function cleanInput($data) {  
  return htmlspecialchars(stripslashes(trim($data)));
}
 
//retrieve the memberid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid bookingId</h2>"; //simple error feedback
        exit;
    } 
}
 
//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) 
    and ($_POST['submit'] == 'Delete')) {     
    $error = 0; //clear our error flag
    $msg = 'Error: ';  
//memberID (sent via a form it is a string not a number so we try a type conversion!)    
    if (isset($_POST['id']) and !empty($_POST['id']) 
        and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid member ID '; //append error message
       $id = 0;  
    }        
//save the member data if the error flag is still clear and member id is > 0
    if ($error == 0 and $id > 0) {
        $query = "DELETE FROM `booking` WHERE bookingId=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'i', $id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>Booking details deleted.</h2>";     
        
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
}
 
//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT * FROM `booking` WHERE bookingId='.$id;
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
<h1>Booking preview before deletion</h1>
<h2><a href='currentbookinglist.php'>[Return to the current bookings]</a> <a href='index.php'>[Return to main page]</a></h2>
<?php


if ($rowcount > 0) {
	
   echo "<fieldset><legend>Booking preview before deletion #$id</legend><dl>";
    /* fetch associative array */
    $row = mysqli_fetch_assoc($result);
         echo "<dt>Room Name:</dt><dd>".$row["roomname"]."</dd>".PHP_EOL;
		        
		echo "<dt>Checkin:</dt><dd>".$row["checkin"]."</dd>".PHP_EOL;
		 echo "<dt>Checkout:</dt><dd>".$row["checkout"]."</dd>".PHP_EOL;				
		
		echo '</dl></fieldset>'.PHP_EOL;
     ?><form method="POST" action="deletebooking.php">
     <h2>Are you sure you want to delete this booking?</h2>
     <input type="hidden" name="id" value="<?php echo $id; ?>">
     <input type="submit" name="submit" value="Delete">
     <a href="currentbookinglist.php">[Cancel]</a>
     </form>
<?php  	 
}else echo "<h2>No booking found!</h2>";
    /* free result set */
    mysqli_free_result($result);

/* close connection */
mysqli_close($DBC);

?> 
  
  
</body>
</html>