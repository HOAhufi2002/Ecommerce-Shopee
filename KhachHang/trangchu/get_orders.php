<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "qlbh_dat");

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

$makhach = $_SESSION['user']['makhach'];
$sql = "SELECT mahoadon, ngaylap, tongtien, trangthai FROM hoadon WHERE makhach = '$makhach' ORDER BY ngaylap DESC";
$result = mysqli_query($conn, $sql);

$orders = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
}

echo json_encode($orders);
mysqli_close($conn);
?>
