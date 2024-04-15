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
    <title>Setup Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <header>
        <!--<a href="index.html" class="logo">-->
            <div class="logo-container">
                <img src="../images/logo.png" alt="Logo" height="50" width="75">
                <span>ExchangExpress</span>
            </div>
        <!--</a>-->
        <h1>Finish Setting Up Your Account</h1>
    </header>

    <main>
        <form action="./bankregis.php" method="get" enctype="multipart/form-data">
            <section>
                <input type="hidden" id="username" name="username" value="<?php echo $uname; ?>">
                <label for="bank-name">Bank Name:</label>
                <input type="text" id="bank-name" name="bank-name" placeholder="Your Bank Name"><br>
                <label for="bank-id">Bank ID:</label>
                <input type="text" id="bank-id" name="bank-id" placeholder="Your Bank ID"><br>
                <label for="card-holder">Card Holder:</label>
                <input type="text" id="card-holder" name="card-holder" placeholder="Your Name"><br>
                <label for="currency">Currency:</label>
                <select id="currency" name="currency">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="JPY">JPY</option>
                    <option value="GBP">GBP</option>
                    <option value="AUD">AUD</option>
                    <option value="IDR">IDR</option>
                </select><br>
                <label for="amount">Amount of Money in Bank:</label>
                <input type="number" min="0" id="amount" name="amount" placeholder="Amount of Money"><br>
                <label for="country">Country:</label>
                <input type="text" id="country" name="country" placeholder="Your Country"><br>
                <label for="transfer-code">Transfer Code:</label>
                <input type="text" id="transfer-code" name="transfer-code" placeholder="Your Transfer Code"><br>
                <!--<label for="bank-pin">Bank Pin:</label>
                <input type="password" id="bank-pin" placeholder="Your Bank Pin"><br>-->
            </section>
    
            <div class="button-container">
                <!--<a href="../dashboard.html">
                    <button id="submit-button">Submit</button>
                </a>-->
                <input type="submit" value="Submit">
            </div>
        </form>
        
    </main>
</body>

</html>