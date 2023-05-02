<?php

session_start();

require_once("../db_connection.php");

$conn = mysqli_connect($servername, $username, $password, $dbname);

$id = (int)$_POST['select1'];

$sql = sprintf("UPDATE SYSTEM_USER SET PASSWORD = '%s' WHERE USER_ID = %d", $_POST['password2'] , $id);

// echo $sql;

mysqli_query($conn, $sql);

mysqli_close($conn);

header('Location:index.php');

?>
