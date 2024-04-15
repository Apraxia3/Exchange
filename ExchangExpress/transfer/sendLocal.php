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
$receiver = $_GET['receiver'];
$username = $_SESSION['username'];
$currency = $_GET['currency'];
$amount = $_GET['amount'];
$admin_fee = $_GET['adminFee'];

$sql = "SELECT user_id FROM account WHERE user_id = (SELECT user_id FROM account where username = '$username')";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$user_id = $row['user_id'];

$sql = "SELECT sender_id FROM sender WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$sender_id = $row['sender_id'];

$sql = "SELECT receiver_id FROM receiver WHERE receiver_holder = '$receiver'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$receiver_id = $row['receiver_id'];

//Insert transaction history
$sql = "INSERT INTO transaction (user_id, sender_id, receiver_id, currency, amount, admin_fee) VALUES 
    (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id, $sender_id, $receiver_id, $currency, $amount, $admin_fee]);

header('Location: ../dashboard.php');
exit();
?>
