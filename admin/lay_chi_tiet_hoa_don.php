<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

// Lấy mã hóa đơn từ URL
$mahoadon = $_GET['mahoadon'];

// Truy vấn chi tiết hóa đơn từ bảng 'chitiethoadon'
$sql = "SELECT chitiethoadon.mahang, chitiethoadon.soluong, chitiethoadon.dongia, chitiethoadon.tongtien, hang.tenhang
        FROM chitiethoadon 
        JOIN hang ON chitiethoadon.mahang = hang.mahang
        WHERE chitiethoadon.mahoadon = $mahoadon";
$result = mysqli_query($conn, $sql);

// Nội dung chi tiết hóa đơn
if (mysqli_num_rows($result) > 0) {
    echo '<table class="table table-hover table-bordered">';
    echo '<thead><tr><th>Mã Hàng</th><th>Tên Hàng</th><th>Số Lượng</th><th>Đơn Giá</th><th>Tổng Tiền</th></tr></thead>';
    echo '<tbody>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['mahang'] . '</td>';
        echo '<td>' . $row['tenhang'] . '</td>';
        echo '<td>' . $row['soluong'] . '</td>';
        echo '<td>' . $row['dongia'] . '</td>';
        echo '<td>' . $row['tongtien'] . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p>Không có chi tiết cho hóa đơn này.</p>';
}

// Đóng kết nối
mysqli_close($conn);

