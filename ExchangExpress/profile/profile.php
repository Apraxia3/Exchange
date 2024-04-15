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

$sql = "SELECT * FROM `account` WHERE username = '$uname'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$email = $row['email'];

$sql = "SELECT * FROM `sender` WHERE user_id = (select user_id from account where username = '$uname')";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$cardholder = $row['sender_holder'];
$bankid = $row['bank_id'];

$sql = "SELECT * FROM `bank` WHERE bank_id = '$bankid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$bankname = $row['bankname'];
$currency = $row['currency'];
$amount = $row['amount'];
$country = $row['country'];
$transfer_code = $row['transfer_code'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Profile Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
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
        <h1>My Profile</h1>
    </header>

    <main>
        <section>
            <h2>Account</h2>
            <p>Username: <span id="username"><?php echo $uname; ?></span></p>
            <p>Email: <span id="email"><?php echo $email; ?></span></p>
        </section>

        <section>
            <h2>Bank Account</h2>
            <p>Bank Name: <span id="bank-name"><?php echo $bankname; ?></span></p>
            <p>Bank ID: <span id="bank-id"><?php echo $bankid; ?></span></p>
            <p>Card Holder: <span id="card-holder"><?php echo $cardholder; ?></span></p>
            <p>Currency: <span id="currency"><?php echo $currency; ?></span></p>
            <p>Country: <span id="country"><?php echo $country; ?></span></p>
            <p>Transfer Code: <span id="transfer-code"><?php echo $transfer_code; ?></span></p>
        </section>

        <div class="button-container">
            <a href="../dashboard.php">
                <button id="dashboard-button">Back to Dashboard</button>
            </a>
            <button id="editButton"><!--onclick="promptPassword()"-->Edit</button>
        </div>
    </main>

    <script>
        const editButton = document.getElementById('editButton');

        editButton.addEventListener('click', function() {
            window.location.href = 'profileEdit.php';
        });
        // function promptPassword() {
        //     var password = prompt("Please enter your password:");
        //     if (password == "<?php //echo $password; ?>") {
        //         window.location.href = "profileEdit.html";
        //     } else {
        //         alert("Incorrect password.");
        //     }
        // }
    </script>
</body>

</html>