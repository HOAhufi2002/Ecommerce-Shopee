<?php
session_start();

// Kết nối cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "qlbh_dat");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Lấy thông tin mã khách hàng từ session (người dùng đã đăng nhập)
$makhach = $_SESSION['user']['makhach'];

// Truy vấn lấy danh sách hóa đơn của khách hàng
$sql = "SELECT mahoadon, ngaylap, tongtien, trangthai FROM hoadon WHERE makhach = '$makhach' ORDER BY ngaylap DESC";
$result = mysqli_query($conn, $sql);
?>

<?php
include 'header.php'; // Bao gồm file header
?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
     <link rel="stylesheet" href="../css/giohang.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Đơn mua của bạn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .status {
            font-weight: bold;
            text-transform: capitalize;
        }
        .status.chua_thanh_toan {
            color: orange;
        }
        .status.da_thanh_toan {
            color: green;
        }
        .status.huy {
            color: red;
        }
    </style>
</head>

<body>

<div class="container">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <h1>Đơn mua của bạn</h1>

    <table>
        <thead>
        <tr>
            <th>Mã hóa đơn</th>
            <th>Ngày lập</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            // Lặp qua danh sách đơn hàng và hiển thị từng đơn
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['mahoadon'] . "</td>";
                echo "<td>" . $row['ngaylap'] . "</td>";
                echo "<td>₫" . number_format($row['tongtien'], 0, '.', '.') . "</td>";
                echo "<td class='status " . $row['trangthai'] . "'>" . $row['trangthai'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Bạn chưa có đơn hàng nào.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

</body>

</html>

<?php
// Đóng kết nối
mysqli_close($conn);
?>
