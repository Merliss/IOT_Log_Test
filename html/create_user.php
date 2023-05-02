<?php

session_start();

require_once("../db_connection.php");

$conn = mysqli_connect($servername, $username, $password, $dbname);

// echo var_dump($_POST);

$group = (int)$_POST['select_field'];
$pass = $_POST['password1'];
$user = $_POST['username1'];

$sql = sprintf("INSERT INTO SYSTEM_USER (LOGIN, PASSWORD, GROUP_ID) VALUES ('%s', '%s', %d)", $user, $pass, $group);

// echo $sql;

$check = sprintf("SELECT * FROM SYSTEM_USER WHERE LOGIN = '%s'", mysqli_real_escape_string($conn,$_POST['username1']));
$result = mysqli_query($conn, $check);

if(mysqli_num_rows($result) == 0){
        mysqli_query($conn, $sql);
}

// mysqli_query($conn, $sql);

mysqli_close($conn);

header('Location:index.php');

?>
