<?php

session_start();

require_once("../db_connection.php");

$conn = mysqli_connect($servername, $username, $password, $dbname);

// echo var_dump($_POST);

$id = (int)$_POST['select2'];

$sql = sprintf("DELETE FROM SYSTEM_USER WHERE USER_ID = %d", $id);

// echo $sql;

mysqli_query($conn, $sql);

mysqli_close($conn);

header('Location:index.php');

?>
