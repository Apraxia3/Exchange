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

$receiver_holder = $_GET['receiver_holder'];
$sql = "DELETE FROM receiver WHERE receiver_holder = '$receiver_holder';";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "Record updated successfully.";
} else {
    echo "Error:";
}

header('Location: ./receiver.php');
exit;

// Close the connection
mysqli_close($conn);
?>
