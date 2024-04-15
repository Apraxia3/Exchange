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
    <title>Transfer Local Page</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/transfer.css">
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
        <h1>Transfer to Local Bank</h1>
    </header>

    <form action="./sendLocal.php" method="get" enctype="multipart/form-data">
        <label for="receiver">Receiver:</label>
        <select id="receiver" name="receiver" onchange="updateCurrency()">
                <?php
                    $sql = "SELECT * FROM receiver where user_id = 
                                (select user_id from account where username = '$uname') and 
                    currency = (select currency from bank where bank_id = 
                                (select bank_id from sender where user_id = (select user_id from account where username = '$uname')));";
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
                            echo '<option value="' . $row['receiver_holder'] . '" data-currency="' . $row['currency'] . '">' . $row['receiver_holder'] . ' | ' . $row['bankname'] . ' | ' . $row['transfer_code'] . ' | ' . $row['currency'] . '</option>';
                ?>
                            <!-- <option value="<?php echo $receiver_holder; ?>">
                                <?php echo $receiver_holder; ?> | <br>
                                <?php echo $bankname; ?> | <br>
                                <?php echo $transfer_code; ?> | <br>
                                <?php echo $currency; ?>
                            </option> -->
                <?php
                        }
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }

                    // Close the connection
                    mysqli_close($conn);
                ?>
        </select>

        <label for="currency">Currency:</label>
        <input type="text" id="currency" name="currency" readonly>

        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" oninput="calculateTotal()">

        <label for="adminFee">Admin Fee (Rupiah):</label>
        <input type="text" id="adminFee" name="adminFee" value="Rp. 2500" readonly>

        <label for="total">Total:</label>
        <input type="number" id="total" name="total" readonly>

        <div class="button-container">
            <button id="pay-button" type="submit">Pay</button>
        </div>
    </form>

    <div class="button-container">
        <button id="cancel-button" type="button" onclick="window.location.href='../dashboard.php'">Cancel</button>
    </div>

    <script>
        function updateCurrency() {
            var receiverSelect = document.getElementById("receiver");
            var currencyInput = document.getElementById("currency");
            
            // Get the selected receiver's currency
            var selectedOption = receiverSelect.options[receiverSelect.selectedIndex];
            var selectedCurrency = selectedOption.getAttribute("data-currency");
            
            // Set the currency input value
            currencyInput.value = selectedCurrency;
        }

        function calculateTotal() {
            var amount = parseFloat(document.getElementById('amount').value);
            var adminFee = parseFloat(document.getElementById('adminFee').value.replace('Rp. ', ''));
            var total = amount + adminFee;
            document.getElementById('total').value = total;
        }

        function checkPin() {
            var pin = prompt("Please enter your bank pin:");
            if (pin === '123') {
                var isConfirmed = confirm("Thank you for your payment! Click OK to continue.");
                if (isConfirmed) {
                    window.location.href = '../dashboard.html';
                } else {
                    setTimeout(function () {
                        window.location.href = '../dashboard.html';
                    }, 3000);
                }
            } else {
                alert("Incorrect pin. Please try again.");
            }
        }
    </script>
</body>

</html>
