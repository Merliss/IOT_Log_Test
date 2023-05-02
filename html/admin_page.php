<?php
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false){
        header('location:index.php');
        exit();
}
else if(!$_SESSION['is_admin']){
	header('location:devices.php');
        exit();
}

require_once("../db_connection.php");

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "Hello";
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Strona administratora</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="admin_page.php">Dashboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="devices.php">Urządzenia</a>
      </li>
    </ul>
    <form method='post' action='logout.php' class="form-inline my-2 my-lg-0">
      <p class = 'mx-4 my-auto'> Witaj <?php echo $_SESSION['username']; ?> </p>
      <button class="btn btn-primary my-2 my-sm-0" type="submit">Wyloguj</button>
    </form>
  </div>
</nav>


<div class="container">
  <div class="container mt-5">
  <h1>Zarządzaj dostępem</h1>
  
  <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true">Dodaj użytkownika</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">Zmień hasło</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="tab3-tab" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab3" aria-selected="false">Usuń użytkownika</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="tab4-tab" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab4" aria-selected="false">Utwórz grupę</a>
    </li>
  </ul>
  
  <div class="tab-content mt-4" id="myTabContent">
    <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="tab1-tab">
      <form method='post' action='create_user.php'>
        <div class="form-group">
          <label for="username1">Nazwa użytkownika:</label>
          <input type="text" class="form-control" id="username1" name="username1">
        </div>
        <div class="form-group">
          <label for="password1">Hasło:</label>
          <input type="password" class="form-control" id="password1" name="password1">
        </div>
	<label for="select_field">Grupa:</label>
        <select class="form-control" id="select_field" name="select_field">
	 <?php
	  $sql = 'SELECT GROUP_ID, GROUP_NAME FROM SYSTEM_GROUP WHERE 1';
	  $result = mysqli_query($conn, $sql);
	  if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			echo '<option value = '. $row['GROUP_ID'] . '>'. $row['GROUP_NAME']. '</option>';
		}
	 }
	 ?>
       </select>
        <button type="submit" class="btn btn-primary my-4">Wyślij</button>
      </form>
    </div>
    
    <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2-tab">
      <form method='post' action='change_password.php'>
        <div class="form-group">
          <label for="select1">Użytkownik:</label>
          <select class="form-control" id="select1" name="select1">
          <?php
          $sql = 'SELECT USER_ID, LOGIN FROM SYSTEM_USER WHERE 1';
          $result = mysqli_query($conn, $sql);
          if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
			echo '<option value = '. $row['USER_ID'] . '>'. $row['LOGIN']. '</option>';
                }
         }
         ?>
          </select>
        </div>
        <div class="form-group">
          <label for="password2">Hasło:</label>
          <input type="password" class="form-control" id="password2" name="password2">
        </div>
          <button type="submit" class="btn btn-primary my-4">Wyślij</button>
      </form>
    </div>
    
    <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
  <form method='post' action='delete_client.php'>
    <div class="form-group">
      <label for="select2">Wybierz użytkownika:</label>
      <select class="form-control" id="select2" name="select2">
      <?php
          $sql = 'SELECT USER_ID, LOGIN FROM SYSTEM_USER WHERE 1';
          $result = mysqli_query($conn, $sql);
          if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                        if($row['LOGIN'] != $_SESSION['username']) echo '<option value = '. $row['USER_ID'] . '>'. $row['LOGIN']. '</option>';
                }
         }
         ?>
      <?php mysqli_close($conn); ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary my-4">Wyślij</button>
  </form>
</div>
     <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
  <form method='post' action='create_group.php'>    
  <div class="form-group">
  <label for="permissions">Uprawnienia:</label>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="permission1" name="MODIFY_USER">
    <label class="form-check-label" for="permission1">Zarządzanie kontami użytkowników</label>
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="permission2" name="CONTROL_DEV">
    <label class="form-check-label" for="permission2">Sterowanie urządzeniami</label>
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="permission3" name="VIEW_DEV_STATUS">
    <label class="form-check-label" for="permission3">Monitorowanie statusu urządzeń</label>
  </div>
  <div class="form-group">
          <label for="groupname1">Nazwa grupy:</label>
          <input type="text" class="form-control" id="groupname1" name="groupname1">
        </div>
</div>
    <button type="submit" class="btn btn-primary my-4">Wyślij</button>
   </form>

     </div>

</div>
</body>
</html>

