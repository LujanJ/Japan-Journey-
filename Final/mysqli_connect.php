<?php 

// This file contains the database access information. 
// This file establishes a connection to MySQL and selects the database.

// Set the database access information as constants:
DEFINE ('DB_USER', 'jl8733');
DEFINE ('DB_PASSWORD', 'Lowrider1');
DEFINE ('DB_HOST', 'satoshi.cis.uncw.edu');
DEFINE ('DB_NAME', 'jl8733');

// Make the connection:
$dbc = mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );
//remove this statement once you know it is working
mysqli_set_charset($dbc,"utf8")
?>