<?php
include "connect.php";

if (isset($_POST['maloai'])) {
    $maloai = $_POST['maloai'];

    // Truy vấn để lấy các sản phẩm thuộc loại hàng được chọn
    $sql = "SELECT hang.mahang, hang.tenhang, hang.mota, loaihang.tenloai, SUBSTRING_INDEX(hang.hinhanh, ',', 1) AS hinh_dau, 
            hang.giamgia, hang.dongia, hang.soluongton, hang.thuonghieu, hang.quocgia 
            FROM hang 
            JOIN loaihang ON hang.maloai = loaihang.maloai 
            WHERE hang.maloai = '$maloai'";
    $result = mysqli_query($conn, $sql);

    // Hiển thị sản phẩm
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $mahang = $row['mahang'];
            $tenhang = $row['tenhang'];
            $hinh = $row['hinh_dau'];  // Hình ảnh đầu tiên
            $don_gia = number_format($row['dongia'], 0, '.', '.');
            echo "
                <div class='grid__colum-2-4'>
                    <a class='home-product-item' href='chitietsp.php?mah=$mahang'>
                        <div class='home-product-item__img' style='background-image: url(\"../img/$hinh\");'></div>
                        <h4 class='home-product-item__name'>$tenhang</h4>
                        <div class='home-product-item__price'>$don_gia VNĐ</div>
                    </a>
                </div>";
        }
    } else {
        echo "Không có sản phẩm nào.";
    }
} else {
    echo "Yêu cầu không hợp lệ.";
}
?>
