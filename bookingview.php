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
  <title>Register customer</title> 
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
</head>
 <body>
 
<?php

    include "config.php"; //load in any variables
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
 
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
    if (isset($_POST['roomname']) and !empty($_POST['roomname']) 
        and is_string($_POST['roomname'])) {
       $fn = cleanInput($_POST['roomname']); 
 //check length and clip if too big
       $roomname = (strlen($fn) > 50)?substr($fn,1,50):$fn; 
       //we would also do context checking here for contents, etc           
    } else {
       $error++; //bump the error flag
       $msg = 'Invalid roomname '; //append error message
       $roomname = '';  
    } 
	 	   	
        $checkin= cleanInput($_POST['checkin']);   $checkout = cleanInput($_POST['checkout']);    
//first and lastname
         $firstname = cleanInput($_POST['firstname']);   
         $lastname = cleanInput($_POST['lastname']);
	  
//phone
       $phone = cleanInput($_POST['phone']);        
//extras and review
       $extras = cleanInput($_POST['extras']);        
       $review = cleanInput($_POST['review']); 
     
  	
//save the member data if the error flag is still clear     	   
		   
 // prepare and bind
$stmt = $DBC->prepare("INSERT INTO `booking` (roomname,checkin,checkout,firstname,lastname,phone,extras,review) VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss",$roomname, $checkin,$checkout,$firstname,$lastname, $phone, $extras,$review);

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
	
  
<h1>Make a Booking</h1>
<h2><a href='currentbookinglist.php'>[Return to the bookings listing]</a><a href='index.php'>[Return to main page]</a></h2>
 
<form method="POST" action="booking.php">
   
 <label for="roomname" >Room (name,type,beds):
	   <select name="roomname">
	    <option value="Kellie" >Kellie, S, 5</option>
		<option value="Herman">Herman, D, 5</option>
	    <option value="Scarlett">Scarlett, D, 2</option>
		<option value="Jelani">Jelani, S, 2</option>
		<option value="Sonya">Sonya, S, 5</option>
	    <option value="Miranda">Miranda, S, 4</option>  
		<option value="Helen">Helen, S, 2</option>
		<option value="Octavia">Octavia, D, 3</option>
	    <option value="Gretchen">Gretchen, D, 3</option>  
		<option value="Bernard">Bernard, S, 5</option>
	    <option value="Dacey">Dacey, D, 2</option>  
		<option value="Preston">Preston, D, 2</option>
		<option value="Dane">Dane, S, 4</option>
	    <option value="Cole">Cole, S, 1</option>  
		  
		</select>
	</label>
		
	<br><br>
    <p>
   <label for="from">Checkin Date:</label>
    <input type="text" id="checkin" name="checkin" placeholder="mm-dd-yyyy" required><br><br>
	</p>
		
    <p><label for="to">Checkout Date:</label>
    <input type="text" id="checkout" name="checkout" placeholder="mm-dd-yyyy" required>	
	<br><br> </p>
  
  <p>
    <label for="firstname">Name: </label>
    <input type="text" id="firstname" name="firstname" minlength="3" maxlength="50" required> 
  </p> 
  <p>
    <label for="lastname">Lastname: </label>
    <input type="text" id="lastname" name="lastname" minlength="3" maxlength="50" required> 
  </p>  
  <p>
    <label for="phone">Phone: </label>
    <input type="text" id="phone" name="phone" minlength="5" maxlength="50"
	pattern="^[\\]{0,1}([0-9]){3}[\\]{0,1}[ ]?([^0-1]){1}([0-9]){2}[ ]?[-]?[ ]?([0-9]){4}[ ]*((x){0,1}([0-9]){1,5}){0,1}$" required> 
	 <small>Format: 123 4567890</small><br><br>
		
  </p>   
  <p>
    <label for="extras">Extras: </label>
    <input type="text" id="extras" name="extras" rows="5" cols="50"> 
  </p>   
   <p>
    <label for="review">Place a Review: </label>
    <input type="text" id="review" name="review" rows="5" cols="50"> 
  </p>  
   <input type="submit" name="submit" value="Register">   
    <a href="currentbookinglist.php">[Cancel]</a>  
</form>


<form>
 <h1>Search room availability</h1> 
   
		 <label for="from">Start date:</label>
         <input type="text" id="checkin" name="checkin" placeholder="mm-dd-yyyy" 
		  onkeyup="searchResult(this.value)" 
         onclick="javascript: this.value = ''">		 
		 
         <label for="to">End date:</label>
         <input type="text" id="checkout" name="checkout" placeholder="mm-dd-yyyy" 	
         onkeyup="searchResult(this.value)"
		  onclick="javascript: this.value = ''">
         <input type ="button" id="driver" value ="Search availability"  onkeyup="searchResult(this.value)" onclick="javascript: this.value = ''">
 
</form>
	
  <div id="roomavlist"> </div>
<br><br>
<hr>

<script>
  $( function() {
    var dateFormat = "mm/dd/yy",
      checkin = $( "#checkin" )
        .datepicker({
		defaultDate: "+1w",		  
          changeMonth: true,
          numberOfMonths: 2
        })
        .on( "change", function() {
          to.datepicker( "option", "minDate", getDate( this ) );
        }),
      checkout = $( "#checkout" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2
      })
      .on( "change", function() {
        from.datepicker( "option", "maxDate", getDate( this ) );
      });
 
    function getDate( element ) {
      var date;
      try {
        date = $.datepicker.parseDate( dateFormat, element.value );
      } catch( error ) {
        date = null;
      }
 
      return date;
    }
  } );
  </script>
 
 <script>
 
function searchResult(searchstr) {
  if (searchstr.length==0) {
    document.getElementById("roomavlist").innerHTML="";
    return;
  }
  xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("roomavlist").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","roomsearch.php?sq="+searchstr,true);
  xmlhttp.send();
}
</script>
 
   
</body>  
  
</html>