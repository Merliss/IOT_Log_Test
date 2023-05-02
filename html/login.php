<?php 
session_start();

require_once("../db_connection.php");

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "Hello";
    die("Connection failed: " . mysqli_connect_error());
}

$post_username=htmlentities($_POST['username'],ENT_QUOTES,"UTF-8");
$post_password=htmlentities($_POST['password'],ENT_QUOTES,"UTF-8");

var_dump($_POST);

$sql = sprintf("SELECT SYSTEM_USER.LOGIN, SYSTEM_GROUP.MODIFY_USER, SYSTEM_GROUP.CONTROL_DEV, SYSTEM_GROUP.VIEW_DEV_STATUS FROM SYSTEM_USER JOIN SYSTEM_GROUP ON SYSTEM_USER.GROUP_ID = SYSTEM_GROUP.GROUP_ID WHERE LOGIN='%s' AND PASSWORD='%s';",
		mysqli_real_escape_string($conn,$post_username),
		mysqli_real_escape_string($conn,$post_password));

// echo $sql;

$result = mysqli_query($conn, $sql);

// Wyświetlenie danych na stronie
if (mysqli_num_rows($result) > 0) {
   // echo "LOGGED_IN";
   $_SESSION['logged_in'] = true;
   $r=$result->fetch_assoc();
   $_SESSION['username'] = $r['LOGIN'];
   $_SESSION['is_admin'] = (bool)(int)$r['MODIFY_USER'];
   $_SESSION['can_control'] = (bool)(int)$r['CONTROL_DEV'];
   $_SESSION['can_view_status'] = (bool)(int)$r['VIEW_DEV_STATUS'];
//   var_dump($r);
}
else{
   $_SESSION['logged_in'] = false;
}
// Zamknięcie połączenia z bazą danych
mysqli_close($conn);

 header('Location:index.php');
?>
