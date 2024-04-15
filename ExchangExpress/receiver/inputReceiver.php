<?php
session_start();
// Database connection information
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'exchange';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the form data
$username = $_SESSION['username'];
$bankname = $_GET['bank-name'];
$receiver_holder = $_GET['receiver'];
$currency = $_GET['currency'];
$transfer_code = $_GET['transfer-code'];

$sql = "INSERT INTO receiver (bankname, currency, receiver_holder, transfer_code, user_id) VALUES 
    (?, ?, ?, ?, (SELECT user_id from account where username = ?))";
$stmt = $conn->prepare($sql);
$stmt->execute([$bankname, $currency, $receiver_holder, $transfer_code, $username]);

header('Location: ./receiver.php');
exit();
?>
