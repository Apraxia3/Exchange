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
$password = $_GET['password'];
$email = $_GET['email'];

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if the username already exists
$sql = "SELECT * FROM account WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$username, $email]);
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Username already exists!";
    sleep(5);
    header('Location: ../index.html');
} else {
    $stmt->close();
    // Insert new user
    $sql = "INSERT INTO account (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $email, $hashed_password]);
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
}

header('Location: ./finishSetup.php');
exit();
?>
