<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mahang = $_POST['mahang'];
    $tenhang = $_POST['tenhang'];
    $dongia = $_POST['dongia'];
    $soluongton = $_POST['soluongton'];
    $mota = $_POST['mota'];
    $current_hinhanh = $_POST['current_hinhanh']; // Ảnh hiện tại

    // Kiểm tra xem người dùng có tải lên ảnh mới không
    if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['size'] > 0) {
        $target_dir = "assets/images/products/";
        $target_file = $target_dir . basename($_FILES["hinhanh"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra xem file có phải là hình ảnh không
        $check = getimagesize($_FILES["hinhanh"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File không phải là hình ảnh.";
            $uploadOk = 0;
        }

        // Kiểm tra nếu tệp đã tồn tại
        if (file_exists($target_file)) {
            echo "File đã tồn tại.";
            $uploadOk = 0;
        }

        // Kiểm tra kích thước file
        if ($_FILES["hinhanh"]["size"] > 500000) { // Giới hạn 500KB
            echo "File quá lớn.";
            $uploadOk = 0;
        }

        // Chỉ cho phép các định dạng file nhất định
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Chỉ cho phép các định dạng JPG, JPEG, PNG.";
            $uploadOk = 0;
        }

        // Kiểm tra nếu có lỗi upload
        if ($uploadOk == 0) {
            echo "Tệp không được tải lên.";
        } else {
            // Nếu không có lỗi, tải tệp lên
            if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file)) {
                echo "Tệp ". htmlspecialchars(basename($_FILES["hinhanh"]["name"])). " đã được tải lên.";
                $hinhanh = basename($_FILES["hinhanh"]["name"]); // Lưu tên file mới
            } else {
                echo "Có lỗi khi tải lên tệp.";
                $hinhanh = $current_hinhanh; // Nếu tải lên lỗi, giữ nguyên ảnh cũ
            }
        }
    } else {
        // Nếu không có file ảnh mới, giữ nguyên ảnh hiện tại
        $hinhanh = $current_hinhanh;
    }

    // Cập nhật sản phẩm trong CSDL
    $sql = "UPDATE hang SET tenhang='$tenhang', dongia='$dongia', soluongton='$soluongton', hinhanh='$hinhanh', mota='$mota' WHERE mahang='$mahang'";
    
    if (mysqli_query($conn, $sql)) {
        // Chuyển hướng về trang danh sách sau khi cập nhật
        header("Location: sanpham.php");
    } else {
        echo "Lỗi khi cập nhật sản phẩm: " . mysqli_error($conn);
    }
}

// Đóng kết nối
mysqli_close($conn);
?>