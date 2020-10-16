<?php
include "checksession.php";
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

   <title>Register customer</title> </head>
 <body>
 
<?php

    include "config.php"; //load in any variables
    $DBC = mysqli_connect(DBUSER, DBPASSWORD, DBDATABASE);
 
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };

//this line is for debugging purposes so that we can see the actual POST data
//echo "<pre>";var_dump($_POST); echo "</pre>";
 echo "</pre>";
//function to clean input but not validate type and content
function cleanInput($data) {  
  return htmlspecialchars(stripslashes(trim($data)));
} 
  
//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit'])and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test
 
 //validate incoming data - only the first field is done for you in this example - rest is up to you to do
//firstname
    $error = 0; //clear our error flag
   $msg = 'Error: ';
    if (isset($_POST['firstname']) and !empty($_POST['firstname']) 
        and is_string($_POST['firstname'])) {
       $fn = cleanInput($_POST['firstname']); 
 //check length and clip if too big
       $firstname = (strlen($fn) > 50)?substr($fn,1,50):$fn; 
       //we would also do context checking here for contents, etc           
    } else {
       $error++; //bump the error flag
       $msg = 'Invalid firstname '; //append error message
       $firstname = '';  
    } 

//lastname
       $lastname = cleanInput($_POST['lastname']);        
//email
       $email = cleanInput($_POST['email']);        
//username
       $username = cleanInput($_POST['username']);        
//password encrypted   		
     $password = cleanInput($_POST['password']); 	
     $password =md5($password);
	   
     $role = cleanInput($_POST['role']);   	  
       
//save the member data if the error flag is still clear
		   // prepare and bind
$stmt = $DBC->prepare("INSERT INTO `customer` (firstname,lastname,email,username,password,role) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssssss", $firstname, $lastname, $email,$username,$password,$role);

// set parameters and execute

$stmt->execute();

$stmt->close();
echo "New records created successfully";

$DBC->close();

}  	

?>

<!-- button for logout-->
    <form method="POST" action="login.php">
    <input type="submit" name="logout" value="Logout">   
    </form>
<h1>New Customer Registration</h1>
<h2><a href='index.php'>[Return to main page]</a></h2>
 
<form method="POST" action="registercustomer.php">
  <p>
    <label for="firstname">Name: </label>
    <input type="text" id="firstname" name="firstname" minlength="3" maxlength="50" required> 
  </p> 
  <p>
    <label for="lastname">Lastname: </label>
    <input type="text" id="lastname" name="lastname" minlength="3" maxlength="50" required> 
  </p>  
  <p>  
    <label for="email">Email: </label>
    <input type="email" id="email" name="email" maxlength="100" size="50" required> 
   </p>
  <p>
    <label for="username">Username: </label>
    <input type="text" id="username" name="username" minlength="5" maxlength="32" required> 
  </p> 

  <p>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" minlength="8" maxlength="32" required> 
  </p> 
  
  <p>
    <label for="role">Role: </label>
    <input type="text" id="role" name="role" maxlength="5" placeholder="User"> 
  </p>   
 
   <input type="submit" name="submit" value="Register"> </form> 
   
 
</body>
 
</html>