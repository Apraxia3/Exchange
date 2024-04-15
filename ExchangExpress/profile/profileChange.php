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
$bankoldid = $_GET["bank-old-id"];
$oldname = $_GET['oldname'];
$email = $_GET["email"];
$username = $_GET['username'];
$bankname = $_GET['bank-name'];
$cardholder = $_GET['card-holder'];
$currency = $_GET['currency'];
$country = $_GET['country'];
$transfer_code = $_GET['transfer-code'];

// Update sender
$sql = "UPDATE sender set sender_holder = ? where 
        user_id = (select user_id from account where username = ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$cardholder, $oldname]);
$result = $stmt->get_result();

// Update account
$sql = "UPDATE account set username = ?, email = ? where 
        username = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $email, $oldname]);
$result = $stmt->get_result();

// Update sender
$sql = "UPDATE bank set bankname = ?, currency = ?, country = ?, transfer_code = ? where 
        bank_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$bankname, $currency, $country, $transfer_code, $bankoldid]);
$result = $stmt->get_result();

header('Location: ./profile.php');
exit();
?>
