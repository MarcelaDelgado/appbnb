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
   <meta charset="UTF-8"/>
   <meta name="viewport" content="width=device-width,initial-scale=1.0"/> 
   <meta http-equiv="X-UA-Compatible" content="ie=edge"/> 
   <title>Edit Review</title> 
   </head>
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
        echo "<h2>Invalid bookingID</h2>"; //simple error feedback
        exit;
    } 
}

/* the data was sent using a form therefore we use the $_POST instead of $_GET
   check if we are saving data first by checking if the submit button exists in
   the array */
if (isset($_POST['submit']) and !empty($_POST['submit'])
    and ($_POST['submit'] == 'Update')) {
       
/* validate incoming data - only the first field is done for 
   you in this example - rest is up to you do*/

$error = 0;
$msg = 'Error: ';

/* memberID (sent via a form it is a string not a number so we try
   a type conversion!) */
    if (isset($_POST['id']) and !empty($_POST['id']) 
        and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']); 
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid bookingID'; //append error message
       $id = 0;  
    }   
	   
//firstname
       $firstname = cleanInput($_POST['firstname']); 
//lastname
       $lastname = cleanInput($_POST['lastname']); 
        
//email
       $review = cleanInput($_POST['review']);        
  
    
//save the member data if the error flag is still clear and member id is > 0
    if ($error == 0 and $id > 0) {
        $query = "UPDATE `booking` SET firstname=?,lastname=?,review=? WHERE bookingID=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'sssi', $firstname, $lastname, $review,$id); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        //echo "<h2>Member details updated.</h2>";    
//out echo and add a redirect///////////////////////////////////////////////////////////////// 
		header('Location: http://mysql02.au.ds.network:3306/bnb/currentbookinglist.php', true, 301);
          die();  
 } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
 
}
//locate the booking to edit by using the bookingID
//we also include the booking ID in our form for sending it back for saving the data
$query = 'SELECT bookingID,firstname,lastname,review FROM `booking` WHERE bookingID='.$id;
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);


?>

<form method="POST" action="login.php">
    <input type="submit" name="logout" value="Logout">   
 </form>
 <?php
checkUser();
loginStatus();
?>	
  <h1>Edit/Add room review</h1>
 <h2><a href='currentbookinglist.php'>[Return to the booking listing]</a> <a href='index.php'>[Return to main page]</a></h2>
 
<form method="POST" action="editreview.php">
  <input type="hidden" name="id" value="<?php echo $id;?>">
  
  
    
  <p>
    <label for="firstname">Name: </label>
    <input type="text" id="firstname" name="firstname" minlength="3" 
           maxlength="50" required value="<?php echo $row['firstname']; ?>"> 
  </p> 
  <p>
    <label for="lastname">Lastname: </label>
    <input type="text" id="lastname" name="lastname" minlength="3" 
           maxlength="50" required value="<?php echo $row['lastname']; ?>"> 
  </p>    
  

  <p>
    <label for="review">Place a Review: </label>
	<textarea id="review" name="review" rows="5" cols="50" value="<?php echo $row['review']; ?>">
   </textarea> <br><br> 
   
  </p>  
  
  
   <input type="submit" name="submit" value="Update">

 </form>  
<?php
} else { 
  echo "<h2>Booking review not found with that ID</h2>"; //simple error feedback

}

mysqli_close($DBC); //close the connection once done
?>
   
</body>
</html>
  