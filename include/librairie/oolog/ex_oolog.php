<?php
#
# Test Programm für Logging
#
#
# some scenarios
#


include "class_oolog.php";
$l=& new oolog("./oolog_test.log", FILE | SCREEN |CLEAR |APPEND);

$l->log("start Logging");
$l->log("debugging with default values", DEBUG);
$l->log("only file", FILE, false, __LINE__);
$l->log("only screen", SCREEN, false, __LINE__);
$l->_die("fatal error 12, exit prg", ALL, false, __LINE__);		#with exit and close
$l->log("output to screen and to file", ALL);
$l->log("debugging only to file:", FILE|DEBUG);     #but is ignored because DEBUG isn't set in class at line 11
$l->closelog();


$l=& new oolog("./oolog_test.log", FILE | SCREEN |CLEAR |HTML);
$l->log("start Logging to screen with html spuuort and plaintext to file which is cleared while opening");
$l->log("debugging with default values", DEBUG);		#here debug is ignored because DEBUG was not set in class
$l->closelog();



$l=& new oolog("./oolog_test.log", FILE | HTML | DEBUG);
$l->log("start Logging to file (append), HTML is ignored while writing to file");
$l->log("debugging with default values", DEBUG, false, __LINE__);		#DEBUG lines will be printed, also line no.
$l->closelog();



######################################################################################
/**
* example file for oolog, did not run!!!
*
* @author Heiko Dillemuth <heiko@dillemuth.de>
* @copyright Heiko Dillemuth (c) 01/2004
* @version 0.00
*
**/



include "class_oolog.php";
$datum=date("Y-m-d");
#
# Logging to file and screen with html output
#
$l=& new oolog("./{$datum}__prg-name.log", FILE | SCREEN | HTML);
#$l=& new oolog("./{$datum}__prg-name.log", FILE | SCREEN | HTML |DEBUG); #print also debug lines
#$l=& new oolog("./{$datum}__prg-name.log", SCREEN | HTML);   #only to screen
#$l=& new oolog("./{$datum}__prg-name.log", FILE | SCREEN | HTML| CLEAR); #screen, file (new clear file)


$l->log("This is PROGRAM XYZ, Version 0.00");

#############################################################
#
# Datenbank öffnen und vorbereiten
#
#############################################################
include_once("connect_db.php");



$l->log("Select Data from database");

$sql = "SELECT datum FROM `pad_abdata_feed` GROUP BY datum ORDER BY datum desc LIMIT 3";

$result = mysql_query($sql, $conn);
$l->log(mysql_error($conn), ALL);					#ALL means: do write this line at all!
if($result)
{
	$number = mysql_num_rows($result);
	$l->log(mysql_error($conn), ALL);
	$l->log("$number Weeks found...", FILE);		#write only to file
}
else
$l->_die("ERROR: no data found!");					#write into log and exit programm


$l->log("Working with data");							#write to log with default flag as set in line 20
#########################################################################

$sql=" select pzn from korr_neu
           join chsronifa on korr_neu.spzn=ifa and ifa_flag=0
			where ttl is NULL";

$repl = mysql_query($sql, $conn);
$l->_die(mysql_error($conn), ALL);					#print mysql error if there is one and exit

$l->log(mysql_num_rows($repl));						#print no. of rows
$l->log(mysql_error($conn), ALL);					#print error if there is one


$l->log("found product", SCREEN|HTML);				#overwrite default from line 54 and log only to screen with html
$l->log("sent to db: $sql", DEBUG);					#write only if DEBUG Flag is set, (=currently not)


mysql_close($conn);
$l->log(mysql_error($conn), ALL);					#ALL for the errors writes to screen and log

$l->log("$mumber = $korr_neu");
$l->log("$mumber Man. Cust. =$manh");
$l->log("Production ready\n");
$l->closelog();
?>