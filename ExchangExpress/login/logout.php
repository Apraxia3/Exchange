<?php
session_start();

$_SESSION['loggedin'] = false;
$_SESSION['username'] = false;
header('Location: ../index.html');
exit;
?>
