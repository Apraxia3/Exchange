<?php
session_start();
if ($_SESSION['loggedin'] == false) {
    header('Location: ../index.html');
    exit;
}
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

$uname = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>History Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/history.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            <a href="profile.php"> <img src="images/profileIcon.png" alt="Profile"> </a>
            <div class="dropdown-menu">
            <a href="profile.php">Profile</a>
            <a href="index.html">Logout</a>
            </div>
        </div>
        </div>
    </header>

    <section class="transaction-history">
        <h2>Transaction History</h2>

        <?php
            $sql = "SELECT * FROM transaction where user_id = (select user_id from account where username = '$uname');";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Access columns by column name
                    $timestamp = isset($row['timestamp']) ? $row['timestamp'] : '';
                    $currency = isset($row['currency']) ? $row['currency'] : '';
                    $amount = isset($row['amount']) ? $row['amount'] :'';
                    $sender_id = isset($row['sender_id']) ? $row['sender_id'] :'';
                    $receiver_id = isset($row['receiver_id']) ? $row['receiver_id'] :'';
                    $admin_fee = isset($row['admin_fee']) ? $row['admin_fee'] : '';

                    $sql = "SELECT sender_holder FROM sender WHERE sender_id = '$sender_id'";
                    $res = $conn->query($sql);
                    $row1 = $res->fetch_assoc();
                    $sender = $row1['sender_holder'];

                    $sql = "SELECT receiver_holder FROM receiver WHERE receiver_id = '$receiver_id'";
                    $res = $conn->query($sql);
                    $row1 = $res->fetch_assoc();
                    $receiver = $row1['receiver_holder'];
        ?>
                    <div class="transaction">
                        <p><strong>Sender:</strong> <?php echo $sender; ?></p>
                        <p><strong>Receiver:</strong> <?php echo $receiver; ?></p>
                        <p><strong>Amount:</strong> <?php echo $currency; ?> : <?php echo $amount; ?></p>
                        <p><strong>Timestamp:</strong> <?php echo $timestamp; ?></p>
                        <p><strong>Admin Fee:</strong> Rp. <?php echo $admin_fee; ?></p>
                    </div>
        <?php
                }
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            // Close the connection
            mysqli_close($conn);
        ?>
    </section>

    <button id="back-button" onclick="window.location.href='dashboard.php'">Back to Dashboard</button>
</body>
</html>