<?php
session_start();

// Kết nối cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "qlbh_dat");

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Kiểm tra nếu giỏ hàng có sản phẩm
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Giỏ hàng rỗng!";
    exit();
}

// Lấy thông tin khách hàng từ session
$makhach = $_SESSION['user']['makhach'];
$ngaylap = date('Y-m-d'); // Ngày lập hóa đơn hiện tại
$tongtien_hd = 0; // Tổng tiền hóa đơn sẽ được tính sau

// Bắt đầu thêm hóa đơn vào bảng `hoadon`
$sql_hoadon = "INSERT INTO hoadon (makhach, ngaylap, tongtien, trangthai) VALUES ('$makhach', '$ngaylap', '$tongtien_hd', 'chua_thanh_toan')";
if (mysqli_query($conn, $sql_hoadon)) {
    // Lấy mã hóa đơn vừa được tạo
    $mahoadon = mysqli_insert_id($conn);

    // Duyệt qua giỏ hàng và thêm chi tiết vào bảng `chitiethoadon`
    foreach ($_SESSION['cart'] as $item) {
        $mahang = $item['mahang'];
        $soluong = $item['so_luong'];
        $dongia = $item['gia_giam']; // Lấy giá sau giảm
        $tongtien_sp = $soluong * $dongia; // Tính tổng tiền của sản phẩm
        
        // Tính tổng tiền cho hóa đơn
        $tongtien_hd += $tongtien_sp;

        // Thêm chi tiết hóa đơn vào bảng `chitiethoadon`
        $sql_chitiethd = "INSERT INTO chitiethoadon (mahoadon, mahang, soluong, dongia, tongtien) 
                          VALUES ('$mahoadon', '$mahang', '$soluong', '$dongia', '$tongtien_sp')";
        mysqli_query($conn, $sql_chitiethd);
    }

    // Cập nhật tổng tiền trong bảng `hoadon`
    $sql_update_hoadon = "UPDATE hoadon SET tongtien = '$tongtien_hd' WHERE mahoadon = '$mahoadon'";
    mysqli_query($conn, $sql_update_hoadon);

    // Xóa giỏ hàng sau khi đã thanh toán
    unset($_SESSION['cart']);

    // Chuyển hướng tới trang xác nhận đơn hàng
    echo "<script>window.location.href='order_confirmation.php';</script>";

} else {
    echo "Lỗi khi tạo hóa đơn: " . mysqli_error($conn);
}

// Đóng kết nối
mysqli_close($conn);
