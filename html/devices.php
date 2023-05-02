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
  <title>Urządzenia</title>
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

<div class="container my-5">
    <h1 class="text-center">Zarządzanie systemem</h1>
    <hr>
    <div class="form-group">
      <label for="messages">Messages:</label>
      <div class="overflow-auto" style="height: 150px;" id="messages">
     </div>
    </div>

<?php if($_SESSION['can_control']) echo ' 
    <div class="form-group">
      <button type="button" class="btn btn-primary mr-3" id="alarm" onclick="armAlarm()">Ustaw alarm</button>
      <button type="button" class="btn btn-danger mr-3" id="clralarm" onclick="clearAlarm()">Czyść alarm</button>
    </div>
	<div class="form-group">
      <button type="button" class="btn btn-success" id="door" onclick="toggleDoor()">Otwórz drzwi</button>
    </div>
    <div class="form-group">
      <button type="button" class="btn btn-secondary" id="light" onclick="toggleLight()">Włącz światło</button>
    </div>
    <div class="form-group">
      <button type="button" class="btn btn-secondary" id="automatic" onclick="toggleAutomatic()">Automatyczne światło</button>
    </div>
    <div class="form-group">
      <label for="time1">Czas świecenia:</label>
      <input type="number" class="form-control" id="time1">
    </div>
    <div class="form-group">
      <button type="button" id="save_time" class="btn btn-warning" onclick="saveTimes1()">Zapisz czas świecenia</button>
    </div>
    <div class="form-group">
      <label for="time2">Czas bez ruchu:</label>
      <input type="number" class="form-control" id="time2">
    </div>
    <div class="form-group">
      <button type="button" id="save_time" class="btn btn-warning" onclick="saveTimes2()">Zapisz czas bez ruchu</button>
    </div>
   ';
?>
</div>
<script>
function addMessage(msg) {
            // Pobieramy element "messages"
            var messagesContainer = document.getElementById("messages");

            // Tworzymy nowy element "p"
            var newMessage = document.createElement("p");

            // Ustawiamy treść nowego elementu
            newMessage.innerHTML = msg;

            // Dodajemy nowy element do kontenera
	    if(messagesContainer.firstChild){
		messagesContainer.insertBefore(newMessage, messagesContainer.firstChild);
	    }
	    else{
                messagesContainer.appendChild(newMessage);
           }
	}


 // Connect to MQTT broker
      var client = new Paho.MQTT.Client("192.168.1.150", 9001, "webClient");
      client.connect({
        onSuccess: onConnect,
        userName: "piuser",
        password: "qwerty"
      });
      function sendMessage(topic, msg) {
        var payload = new Paho.MQTT.Message(msg);
        payload.destinationName = topic;
        client.send(payload);
      }

      // Subscribe to topic
      function onConnect() {
        console.log("Connected to MQTT broker.");
        client.subscribe("test");
        client.subscribe("alarm");
        client.subscribe("setting/light");
        client.subscribe("setting/door");
        client.send("general", "init");
      }
      
      // Display received messages
      client.onMessageArrived = function(message) {
        // var messages = document.getElementById("messages");
        // messages.insertAdjacentHTML("beforeend", "<p>" + message.payloadString + "</p>");
	var now = new Date();
	var year = now.getFullYear();
	var month = (now.getMonth() + 1).toString().padStart(2, '0');
	var day = now.getDate().toString().padStart(2, '0');
	var hour = now.getHours().toString().padStart(2, '0');
	var minute = now.getMinutes().toString().padStart(2, '0');
	var second = now.getSeconds().toString().padStart(2, '0');

	var datetimeString = `${year}-${month}-${day} ${hour}:${minute}:${second}`;

	var msg = datetimeString + "  " + message.destinationName + ": " + message.payloadString;
	addMessage(msg);
      }

	function armAlarm() {
      let button = document.querySelector('#alarm');
      if (button.innerHTML === "Ustaw alarm") {
        button.innerHTML = "Wyłącz alarm";
      } else {
        button.innerHTML = "Ustaw alarm";
      }
    }
function clearAlarm() {
  let messages = document.querySelector('#messages');
  sendMessage("alarm", "clear");
}

function toggleDoor() {
  let button = document.querySelector('#door');
  if (button.innerHTML === "Otwórz drzwi") {
    button.innerHTML = "Zamknij drzwi";
    sendMessage("setting/door", "on");
  } else {
    button.innerHTML = "Otwórz drzwi";
    sendMessage("setting/door", "off");
  }
}

function saveTimes1() {
  let time1 = document.querySelector('#time1').value;
  sendMessage("setting/light/ontime", `${time1}`);
}

function saveTimes2() {
  let time2 = document.querySelector('#time2').value;
  sendMessage("setting/light/offtime", `${time2}`);
}

function toggleLight() {
  let button = document.querySelector('#light');
  if (button.innerHTML === "Włącz światło") {
    button.innerHTML = "Wyłącz światło";
    sendMessage("setting/light", "on");
  } else {
    button.innerHTML = "Włącz światło";
    sendMessage("setting/light", "off");
  }
}
function toggleAutomatic() {
  let button = document.querySelector('#automatic');
  if (button.innerHTML === "Automatyczne światło") {
    button.innerHTML = "Światło manual";
    sendMessage("setting/light", "auto")
  } else {
    button.innerHTML = "Automatyczne światło";
    sendMessage("setting/light", "man");
  }
}
</script>
</body>
</html>

