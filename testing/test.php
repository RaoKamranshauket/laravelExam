<?php
/*
$servername = "161.35.51.210";
$username = "testuser1";
$password = "lkET~ULF#>%GAr)VG{@@(P1a^";
 
$mysql_database ="new_portal";
$mysql_user ="root";
$mysql_password ="lkET~ULF#>%GAr)VG{@@(P1a^";
$mysql_hostname ="161.35.51.210";
*/

$mysql_database ="kenlarvalawsvpmyprdb";
$mysql_user ="kenlarvalawsvpmyprdb";
$mysql_password ="cv9u.bbehDc9M";
$mysql_hostname ="107.22.145.6";
 


/* 
$mysqli = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();  
}
echo "Connected successfully";
*/


$mysqli = new mysqli($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

// Perform query
if ($result = $mysqli -> query("SELECT * FROM kenlarvalawsvpmyprdb.jobs")) {
  echo "Returned rows are: " . $result -> num_rows;
  // Free result set
  $result -> free_result();
}

$mysqli -> close();


 
 
/* 
$connection = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password);
mysqli_select_db($mysql_database);

$ret=mysqli_query( "SELECT * FROM new_portal.new_table11" );
$row_count = mysqli_num_rows($ret);
												
echo "Connected successfully";		
echo $row_count;		
*/

?> 
