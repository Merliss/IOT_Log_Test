<?php
// Tworzenie połączenia z bazą danych
$servername = "localhost";
$username = "dbuser";
$password = "qwerty";
$dbname = "test_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Sprawdzenie czy połączenie z bazą danych zostało nawiązane poprawnie
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Pobranie danych z tabeli
$sql = "SELECT record_id, record_text FROM test_table";
$result = mysqli_query($conn, $sql);

// Wyświetlenie danych na stronie
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "Record ID: " . $row["record_id"]. " - Record Text: " . $row["record_text"]. "<br>";
    }
} else {
    echo "No records found.";
}

// Zamknięcie połączenia z bazą danych
mysqli_close($conn);
?>

