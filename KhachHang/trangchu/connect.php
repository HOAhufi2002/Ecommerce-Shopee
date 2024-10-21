<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
@session_start();
$servername = "localhost";
$dbname = "qlbh_dat";
$username = "root";
$password = "";
$conn = mysqli_connect($servername, $username, $password, $dbname);
?>

</body>
</html>