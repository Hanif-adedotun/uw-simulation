<?php 
// Msql database connection
$username = 'utility';
$password = '6t0lMXHLz02uuNir';
$port = 'localhost';

// Database name
$db = 'simulation' ;

// Connect to the Database
$con = mysqli_connect($port, $username, $password, $db);

if($con->connect_error){
     echo 'Cannot connect to Server, check connection' . $con->connect_error;
}

// Table names in the database

// First table: User table
$tb_users = 'users';
$tb_users_field = array('id', 'name', 'email', 'password', 'company');

// Second table: Apllications table
$tb_applications = 'applications';
$tb_app_fields = array('id', 'size', 'version', 'compatibility', 'downloads', 'status', 'age group', 'date of release', 'logo', 'developer id');



// Function to authenticate every request to make sure it is coming from the right source
$authName = 'Uwsimulation';
$authPW = '3n$5tsds';
     
?>