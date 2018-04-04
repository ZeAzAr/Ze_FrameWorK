<?php
require_once("class.session.php");

$obj_session = new session(); /**< Create a new instance of the class Session and this is enough to start the new session.*/

$_SESSION['str_var'] = "anything";
$_SESSION['int_var'] = 19;
$_SESSION['bool_var'] = true;
echo "test";
?>
Session is Changed in this page<br/>
<a href="test2.php">Click here to show what is put in session</a>