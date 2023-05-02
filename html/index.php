
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Strona logowania</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 mt-5">
                <h3 class="text-center mb-4">Zaloguj się do systemu</h3>
                <form method="post" action="login.php">
                    <div class="form-group">
                        <label for="username">Nazwa użytkownika:</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Podaj nazwę użytkownika">
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Podaj hasło">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Zaloguj</button>
                </form>
            </div>
        </div>
	<?php session_start(); if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) header('location:admin_page.php'); ?>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

