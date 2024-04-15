<?php
session_start();
if ($_SESSION['loggedin'] == false) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dashboard.css">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <a href="dashboard.php" class="logo">
            <div class="logo-container">
                <img src="images/logo.png" alt="Logo" height="50" width="75">
                <span>ExchangExpress</span>
            </div>
        </a>
        <div class="profile-icon">
            <a href="./profile/profile.php"> <img src="images/profileIcon.png" alt="Profile"> </a>
            <div class="dropdown-menu">
            <a href="./profile/profile.php">Profile</a>
            <a href="./login/logout.php">Logout</a>
            </div>
        </div>
        </div>
    </header>
    <h1 class="currency-today">
        Currency Today (<span id="date-time"></span>)
    </h1>

    <h1 class="wanna-transfer">
        Wanna Transfer?
    </h1>
    <div class="grid-container">
        <a href="./receiver/receiver.php" class="grid-item">Receiver List</a>
        <a href="history.php" class="grid-item">Transaction History</a>
        <a href="./transfer/transferLocal.php" class="grid-item">Transfer to Local Bank (Admin Fee +2.500)</a>
        <a href="./transfer/transferForeign.php" class="grid-item">Transfer to Foreign Bank (Admin Fee +10.000)</a>
    </div>
    <script>
        window.onload = function() {
            var now = new Date();
            var dateTimeString = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
            document.getElementById('date-time').textContent = dateTimeString;
        }
    </script>
</body>
</html>