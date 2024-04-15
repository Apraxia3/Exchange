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
        <h1>Edit My Profile</h1>
    </header>

    <main>
        <form action="./profileChange.php" method="get" enctype="multipart/form-data">
            <section>
                <h2>Account</h2>
                <input type="hidden" id="oldname" name="oldname" value="<?php echo $uname; ?>">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $uname; ?>" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required><br>
            </section>
        
            <section>
                <h2>Bank Account</h2>
                <input type="hidden" id="bank-old-id" name="bank-old-id" value="<?php echo $bankid; ?>"><br>
                <label for="bank-name">Bank Name:</label>
                <input type="text" id="bank-name" name="bank-name" value="<?php echo $bankname; ?>" required><br>
                <!--<label for="bank-id">Bank ID:</label>
                <input type="text" id="bank-id" name="bank-id" value="<?php echo $bankid; ?>" required><br>-->
                <label for="card-holder">Card Holder:</label>
                <input type="text" id="card-holder" name="card-holder" value="<?php echo $cardholder; ?>" required><br>
                <label for="currency">Currency:</label>
                <select id="currency" name="currency" readonly>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="JPY">JPY</option>
                    <option value="GBP">GBP</option>
                    <option value="AUD">AUD</option>
                    <option value="IDR">IDR</option>
                </select><br>
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" value="<?php echo $country; ?>" required><br>
                <label for="transfer-code">Transfer Code:</label>
                <input type="text" id="transfer-code" name="transfer-code" value="<?php echo $transfer_code; ?>" required><br>
            </section>
        
            <div class="button-container">
                <a href="./profile.php">
                    <button id="cancel-button">Cancel</button>
                </a>
                <a href="./profileChange.php">
                    <button type="submit" id="save-button">Save</button>
                </a>
            </div>
        </form>
    </main>
</body>
</html>