
<?php

session_start();

require_once("../db_connection.php");

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "Hello";
    die("Connection failed: " . mysqli_connect_error());
}

// echo $_POST['groupname1'] . '<br>';

$modify = 0;
$control = 0;
$view = 0;


if($_POST['MODIFY_USER'] == 'on'){
	$modify = 1;
}

if($_POST['CONTROL_DEV'] == 'on'){
	$control = 1;
}

if($_POST['VIEW_DEV_STATUS'] == 'on'){
	$view = 1;
}

$sql = sprintf("INSERT INTO SYSTEM_GROUP (GROUP_NAME, MODIFY_USER, CONTROL_DEV, VIEW_DEV_STATUS) VALUES ('%s', %d, %d, %d)",
                mysqli_real_escape_string($conn,$_POST['groupname1']), $modify, $control, $view);

$check = sprintf("SELECT * FROM SYSTEM_GROUP WHERE GROUP_NAME = '%s'", mysqli_real_escape_string($conn,$_POST['groupname1']));
// echo $sql;
$result = mysqli_query($conn, $check);

if(mysqli_num_rows($result) == 0){
	mysqli_query($conn, $sql);
}

// mysqli_query($conn, $sql);

mysqli_close($conn);

header('Location:index.php');

?>
