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
    <title>Receiver List Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/receiver.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <header>
        <a href="../dashboard.php" class="logo">
            <div class="logo-container">
                <img src="../images/logo.png" alt="Logo" height="50" width="75">
                <span>ExchangExpress</span>
            </div>
        </a>
        <div class="profile-icon">
            <a href="../profile/profile.php"> <img src="../images/profileIcon.png" alt="Profile"> </a>
            <div class="dropdown-menu">
                <a href="../profile/profile.php">Profile</a>
                <a href="../index.html">Logout</a>
            </div>
        </div>
        </div>
    </header>

    <body>
        <table>
            <thead>
                <tr>
                    <th>Bank Name</th>
                    <th>Currency</th>
                    <th>Holder</th>
                    <th>Transfer Code</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * FROM receiver where user_id = (select user_id from account where username = '$uname');";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Access columns by column name
                            $bankname = isset($row['bankname']) ? $row['bankname'] : '';
                            $currency = isset($row['currency']) ? $row['currency'] : '';
                            $receiver_holder = isset($row['receiver_holder']) ? $row['receiver_holder'] :'';
                            $transfer_code = isset($row['transfer_code']) ? $row['transfer_code'] : '';

                            $data = array(
                                "bankname" => $bankname,
                                "currency" => $currency,
                                "receiver_holder" => $receiver_holder,
                                "transfer_code" => $transfer_code
                            );
                            $json_data = json_encode($data);
                ?>
                            <tr>
                                <td><?php echo $bankname; ?></td>
                                <td><?php echo $currency; ?></td>
                                <td><?php echo $receiver_holder; ?></td>
                                <td><?php echo $transfer_code; ?></td>
                                <td><a href="./receiverdelete.php?receiver_holder=<?php echo $receiver_holder; ?>">Delete</a></td>
                            </tr>
                <?php
                        }
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }

                    // Close the connection
                    mysqli_close($conn);
                ?>
            </tbody>
        </table>

        <div class="button-container">
            <a href="../dashboard.php">
                <button id="dashboard-button" class="green-button">Back to Dashboard</button>
            </a>
            <a href="./insertReceiver.html">
                <button id="insert-button" class="green-button">Insert Receiver</button>
            </a>
        </div>
        <script>
            function confirmDelete(btn) {
                var r = confirm("Are you sure you want to delete this receiver?");
                if (r == true) {
                    var row = btn.parentNode.parentNode;
                    row.parentNode.removeChild(row);
                } else {
                    // If the user clicked "Cancel", do nothing
                }
            }
        </script>
    </body>
</body>

</html>