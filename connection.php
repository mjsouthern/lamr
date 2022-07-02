<?php
$servername = 'remotemysql.com';
$username = 'OrkB5sKFpZ';
$password = 'cUkePiTeEU';
$dbname = 'OrkB5sKFpZ';

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die($conn->connect_error);
}
