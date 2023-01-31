<?php
$servername = '103.123.40.149';
$username = 'root';
$password = '';
$dbname = 'marlon';

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die($conn->connect_error);
}
