<?php
$servername = '80.80.80.100';
$username = 'root';
$password = '';
$dbname = 'marlon';

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die($conn->connect_error);
}
