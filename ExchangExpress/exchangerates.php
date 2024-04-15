<?php
// Database connection settings
$dbhost = "localhost";
$dbname = "exchange";
$dbuser = "root";
$dbpass = "";

// Establish a connection to MySQL
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch data from exchangeratesapi
$api_url = "http://api.exchangeratesapi.io/v1/latest?access_key=44effa89f945e1b3da2ff9aea2d70284b";
$response = file_get_contents($api_url);

// Check if response is received
if ($response === false) {
    die("Failed to fetch data from API");
}

// Decode JSON response
$data = json_decode($response, true);

// Check if decoding was successful
if ($data === null || !isset($data['rates'])) {
    die("Failed to decode JSON response");
}

// Prepare and execute insert statement
$stmt = mysqli_prepare($conn, "INSERT INTO currency (currency, country, idr_amount) VALUES (?, ?, ?)");

if ($stmt === false) {
    die("Error in preparing statement: " . mysqli_error($conn));
}

// Define array to map currency codes to country names
$country_map = array(
    "USD" => "United States",
    "EUR" => "European Union",
    "GBP" => "United Kingdom",
    "JPY" => "Japan",
    "CAD" => "Canada",
    "AUD" => "Australia",
    "CHF" => "Switzerland",
    "CNY" => "China",
    "SEK" => "Sweden",
    "NZD" => "New Zealand",
    "IDR" => "Indonesia",
);

foreach ($data['rates'] as $currency => $rate) {
    $amount_in_idr = $rate * $data['rates']['IDR'];
    $country = isset($country_map[$currency]) ? $country_map[$currency] : "Unknown";

    mysqli_stmt_bind_param($stmt, "ssd", $currency, $country, $amount_in_idr);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_errno($stmt) !== 0) {
        echo "Error in executing statement: " . mysqli_stmt_error($stmt);
    }
}

// Close statement
mysqli_stmt_close($stmt);

// Close MySQL connection
mysqli_close($conn);

echo "Data inserted successfully!";
?>
