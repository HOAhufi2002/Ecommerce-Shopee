<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mahang = $_POST['mahang'];
    $tenhang = $_POST['tenhang'];
    $dongia = $_POST['dongia'];
    $soluongton = $_POST['soluongton'];
    $mota = $_POST['mota'];

    // Xử lý upload hình ảnh
    $target_dir = "assets/images/products/";
    $target_file = $target_dir . basename($_FILES["hinhanh"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra xem tệp có phải là hình ảnh không
    $check = getimagesize($_FILES["hinhanh"]["tmp_name"]);
    if($check !== false) {
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

    // Kiểm tra kích thước tệp
    if ($_FILES["hinhanh"]["size"] > 500000) { // Giới hạn 500KB
        echo "File quá lớn.";
        $uploadOk = 0;
    }

    // Chỉ cho phép một số định dạng file nhất định
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
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
            
            // Lưu thông tin vào CSDL
            $hinhanh = basename($_FILES["hinhanh"]["name"]); // Lưu tên file vào CSDL
            $sql = "INSERT INTO hang (mahang, tenhang, dongia, soluongton, hinhanh, mota) 
                    VALUES ('$mahang', '$tenhang', '$dongia', '$soluongton', '$hinhanh', '$mota')";

            if (mysqli_query($conn, $sql)) {
                // Chuyển hướng về trang danh sách sau khi thêm thành công
                header("Location: sanpham.php");
            } else {
                echo "Lỗi khi thêm sản phẩm: " . mysqli_error($conn);
            }
        } else {
            echo "Có lỗi khi tải lên tệp.";
        }
    }
}

// Đóng kết nối
mysqli_close($conn);

?>