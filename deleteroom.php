<!DOCTYPE HTML>
<?php
include "checksession.php";
checkUser();
loginStatus(); 
include "header.php";
include "menu.php";
echo '<div id="site_content">';
echo '<div id="content">';

?>
<html><head><title>Delete Room</title> </head>
 <body>
<?php
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (!$DBC) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
};
//this line is for debugging purposes so that we can see the actual POST/GET data
//echo "<pre>"; var_dump($_POST); var_dump($_GET);echo "</pre>";

function cleanInput($data) {
	
	return htmlspecialchars(stripslashes(trim($data)));
}
//retrieve the memberid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid roomID</h2>"; //simple error feedback
        exit;
    } 
}
/* the data was sent using a form therefore we use the $_POST instead of $_GET
   check if we are saving data first by checking if the submit button exists in
   the array */
if (isset($_POST['submit']) and !empty($_POST['submit'])
    and ($_POST['submit'] == 'Delete')) {
       
/* validate incoming data - only the first field is done for 
   you in this example - rest is up to you do*/

$error = 0;
$msg = 'Error: ';

////////////////////////////////////////////
/* roomID (sent via a form it is a string not a number so we try
   a type conversion!) */
    if (isset($_POST['id']) and !empty($_POST['id']) 
        and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid roomID'; //append error message
       $id = 0;  
    } 
	
	   $roomname = cleanInput($_POST['roomname']);   
       $description = cleanInput($_POST['description']);
       $roomtype = cleanInput($_POST['roomtype']); 
       $beds = cleanInput($_POST['beds']); 

 if ($error == 0 and $id > 0) {
	 
	   $query = "DELETE FROM `room` WHERE roomId=?";        
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'i',$id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        //echo "<h2>Member details updated.</h2>";    
//out echo and add a redirect///////////////////////////////////////////////////////////////// 
		header('Location: http://mysql02.au.ds.network:3306/listrooms.php', true, 301);
          die();  
 } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }    
 }

//prepare a query and send it to the server
//NOTE for simplicity purposes ONLY we are not using prepared queries
//make sure you ALWAYS use prepared queries when creating custom SQL like below
$query = 'SELECT * FROM `room` WHERE roomId='.$id;
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
<h1>Room view</h1>
<h2><a href='listrooms.php'>[Return to the room listing]</a> <a href='index.php'>[Return to main page]</a></h2>

<?php

if ($rowcount > 0) {
	
   echo "<fieldset><legend>Room preview before deletion #$id</legend><dl>";
    /* fetch associative array */
    $row = mysqli_fetch_assoc($result);
         echo "<dt>Room Name:</dt><dd>".$row["roomname"]."</dd>".PHP_EOL;
		        
		echo "<dt>Description:</dt><dd>".$row["description"]."</dd>".PHP_EOL;
		 echo "<dt>Roomtype:</dt><dd>".$row["roomtype"]."</dd>".PHP_EOL;
				
		
		echo '</dl></fieldset>'.PHP_EOL;
     ?>
	 <form method="POST" action="deleteroom.php">
     <h2>Are you sure you want to delete this room?</h2>
     <input type="hidden" name="id" value="<?php echo $id; ?>">
     <input type="submit" name="submit" value="Delete">
     <a href="listrooms.php">[Cancel]</a>
     </form>
<?php
} else { 
  echo "<h2>Room not found with that ID</h2>"; //simple error feedback

}

mysqli_close($DBC); //close the connection once done
?>

</body>
</html>
  