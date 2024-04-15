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
$username = $_GET['username'];
$bankname = $_GET['bank-name'];
$bankid = $_GET['bank-id'];
$cardholder = $_GET['card-holder'];
$currency = $_GET['currency'];
$amount = $_GET['amount'];
$country = $_GET['country'];
$transfer_code = $_GET['transfer-code'];

// Check if the sender already exists
$sql = "SELECT * FROM sender WHERE user_id = (SELECT user_id FROM account where username = ?)
        OR bank_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $bankid]);
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Sender already exists!";
    sleep(5);
    header('Location: ./finishSetup.php');
} else {
    $stmt->close();
    // Insert new bank
    $sql = "INSERT INTO bank (bank_id, bankname, currency, amount, country, transfer_code) VALUES 
    (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$bankid, $bankname, $currency, $amount, $country, $transfer_code]);

    //insert new sender
    $sql = "INSERT INTO sender (sender_holder, bank_id, user_id) VALUES 
    (?, ?, (select user_id from account where username = ?))";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$cardholder, $bankid, $username]);
}

$sender = $_SESSION['sender'];
header('Location: ../dashboard.php');
exit();
?>
