<?php

echo '<div id="site_content">';
include "sidebar.php";
echo '<div id="content">';
include "content.php";
echo '</div></div>';

?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Ongaonga Bed & Breakfast</title>
  <meta name="description" content="Ongaonga Bed & Breakfast" />
  <meta name="keywords" content="Bed & Breakfast" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style/style.css" title="style" />
</head>

<body>

	<div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="/bnb/"><span class="logo_colour">Ongaonga Bed & Breakfast</span></a></h1>
          <h2>Make yourself at home is our slogan. We offer some of the best beds on the east coast. Sleep well and rest well.</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li class="selected"><a href="index.php">Home</a></li>
          <li><a href="listrooms.php">Rooms</a></li>
          <li><a href="booking.php">Booking</a></li>
	<!-- register link pointing to login.php ----we will not including any user control for now-->		  
          <li><a href="login.php">Register</a></li>
          <li><a href="login.php">Login</a></li>
		 </ul>

      </div>

    </div>
    <div id="footer">
      Copyright &copy; Ongaonga Bed and Breakfast 2020 | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
  </div>

</body>

<!--
<div id="main">-->

</html>

