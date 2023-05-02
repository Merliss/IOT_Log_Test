<?php
session_start();
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false){
        header('location:index.php');
        exit();
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.js" type="text/javascript"></script>
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


<div id="messages"></div>

<script>
 // Connect to MQTT broker
      var client = new Paho.MQTT.Client("192.168.1.150", 9001, "webClient");
      client.connect({
        onSuccess: onConnect,
        userName: "piuser",
        password: "qwerty"
      });
      
      // Subscribe to topic
      function onConnect() {
        console.log("Connected to MQTT broker.");
        client.subscribe("test");
      }
      
      // Display received messages
      client.onMessageArrived = function(message) {
        var messages = document.getElementById("messages");
        messages.insertAdjacentHTML("beforeend", "<p>" + message.payloadString + "</p>");
      }
      
      // Send message
      function sendMessage() {
        var topic = document.getElementById("topic").value;
        var message = document.getElementById("message").value;
        var payload = new Paho.MQTT.Message(message);
        payload.destinationName = topic;
        client.send(payload);
      }
</script>
</body>
</html>
