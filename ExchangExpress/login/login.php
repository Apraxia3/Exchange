<?php
session_start();

if (isset($_GET['username']) && isset($_GET['password'])) {
    $_SESSION['loggedin'] = false;
    $_SESSION['username'] = $_GET['username'];
    $password = $_GET['password'];
    $username = $_SESSION['username'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

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

    $sql = "SELECT * FROM `account` WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result) {
        //$row = $result->fetch_assoc();
        //if ($row && password_verify($hashed_password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            header('Location: ../dashboard.php');
            exit;
        // } else {
        //     echo "Username / Password is Incorrect!";
        // }
    } else {
        echo "Error in SQL query";
    }
}

header('Location: ../index.html');
exit;
?>
