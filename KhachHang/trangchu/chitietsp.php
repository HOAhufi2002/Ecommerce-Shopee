<?php
session_start(); // Khởi động session trước khi bao gồm header hoặc bất kỳ mã HTML nào
function checkLoggedIn() {
    if (!isset($_SESSION['user'])) {
        // Nếu người dùng chưa đăng nhập, chuyển hướng tới trang đăng nhập
        header('Location: login.php');
        exit();
    }
}

include 'header.php'; // Bao gồm file header
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/chitietsp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Chi Tiết Sản Phẩm</title>
</head>

<body>
    <div class="app">

        <?php
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

// Nếu đã đăng nhập, xử lý thêm sản phẩm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mahang'])) {
    $mahang = $_POST['mahang'];
    $tenhang = $_POST['tenhang'];
    $hinh = $_POST['hinh'];
    $gia = $_POST['gia'];
    $gia_goc = $_POST['gia_goc']; // Thêm giá gốc
    $loai = $_POST['loai']; // Thêm loại hàng
    $so_luong = $_POST['so_luong'];

    // Kiểm tra nếu giỏ hàng đã tồn tại trong session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = []; // Khởi tạo giỏ hàng nếu chưa có
    }

    // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['mahang'] == $mahang) {
            $item['so_luong'] += $so_luong; // Cập nhật số lượng
            $product_exists = true;
            break;
        }
    }

    // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'mahang' => $mahang,
            'tenhang' => $tenhang,
            'hinh' => $hinh,
            'gia_giam' => $gia,
            'gia_goc' => $gia_goc, // Thêm giá gốc
            'loai' => $loai, // Thêm loại hàng
            'so_luong' => $so_luong,
        ];
    }

    // Chuyển hướng lại trang giỏ hàng
    echo "<script>window.location.href='giohang.php';</script>";
    exit();
}

        ?>

        <div class="app__container1">
            <div class="grid">
                <div class="grid__row app__content">
                    <?php
                    // Kết nối đến cơ sở dữ liệu
                    $conn = mysqli_connect("localhost", "root", "", "qlbh_dat");

                    // Kiểm tra kết nối
                    if (!$conn) {
                        die("Kết nối thất bại: " . mysqli_connect_error());
                    }

                    // Lấy mã sản phẩm từ query string
                    if (isset($_GET['mah'])) {
                        $mahang = $_GET['mah'];

                        // Truy vấn thông tin sản phẩm
                        $query = "SELECT hang.mahang, hang.tenhang, hang.mota, loaihang.tenloai, hang.hinhanh, hang.giamgia, hang.dongia, hang.soluongton, hang.thuonghieu, hang.quocgia 
                FROM hang 
                JOIN loaihang ON hang.maloai = loaihang.maloai 
                WHERE hang.mahang = '$mahang'";

                        $result = mysqli_query($conn, $query);

                        // Kiểm tra nếu sản phẩm tồn tại
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $tenhang = $row['tenhang'];
                            $mota = $row['mota'];
                            $nhom = $row['tenloai'];
                            $hinh = $row['hinhanh'];
                            $don_gia = $row['dongia'];
                            $giam_gia = $row['giamgia'];
                            $so_luong = $row['soluongton'];
                            $thuonghieu = $row['thuonghieu'];
                            $quocgia = $row['quocgia'];

                            // Tính toán giá sau khi giảm
                            $gia_goc = number_format($don_gia, 0, '.', '.');
                            $gia_giam = number_format($don_gia - $giam_gia, 0, '.', '.');

                            // Tính toán % giảm giá
                            $phan_tram_giam = 0;
                            if ($don_gia > 0) {
                                $phan_tram_giam = ($giam_gia / $don_gia) * 100;
                            }

                            // Thay đổi cách hiển thị số lượng
                            $so_luong_hien_thi = number_format($so_luong, 0, '.', '.');

                            // Xử lý hình ảnh
                            $images = explode(',', $hinh);

                            // Nếu chỉ có 1 ảnh, lặp lại ảnh đó cho 5 thumbnail
                            if (count($images) == 1) {
                                $images = array_fill(0, 5, $images[0]);
                            }

                            // Truy vấn màu sắc
                            $query_mau = "SELECT mau.mau_id, mau.mau_name 
                    FROM mau 
                    JOIN hang_size_mau ON mau.mau_id = hang_size_mau.mau_id 
                    WHERE hang_size_mau.mahang = '$mahang'";
                            $result_mau = mysqli_query($conn, $query_mau);

                            // Truy vấn kích thước
                            $query_size = "SELECT size.size_id, size.size_name 
                    FROM size 
                    JOIN hang_size_mau ON size.size_id = hang_size_mau.size_id 
                    WHERE hang_size_mau.mahang = '$mahang'";
                            $result_size = mysqli_query($conn, $query_size);

                    ?>
                            <div class="product-left">
                                <!-- Hình ảnh chính -->
                                <img src="../img/<?php echo $images[0]; ?>" alt="Main Product Image" class="main-image" id="mainImage">

                                <!-- Hình ảnh thu nhỏ -->
                                <div class="thumbnail-images">
                                    <button class="thumbnail-arrow left" onclick="prevImage()">&#10094;</button>
                                    <div class="thumbnail-images-wrapper">
                                        <?php foreach ($images as $img) { ?>
                                            <img src="../img/<?php echo $img; ?>" alt="Thumbnail" class="thumbnail" onclick="changeImage(this)">
                                        <?php } ?>
                                    </div>
                                    <button class="thumbnail-arrow right" onclick="nextImage()">&#10095;</button>
                                </div>
                            </div>

                            <div class="product-right">
                                <h1><?php echo $tenhang; ?></h1>
                                <div class="price">
                                    <span class="discount-price">₫<?php echo $gia_giam; ?></span>
                                    <span class="original-price">₫<?php echo $gia_goc; ?></span>
                                    <span class="sale"><?php if ($phan_tram_giam > 0) echo round($phan_tram_giam) . '% GIẢM'; ?></span>
                                </div>

                                <div class="product-info">
                                    <div class="row">
                                        <span class="label">Loại hàng:</span>
                                        <span class="value"><?php echo $nhom; ?></span>
                                    </div>
                                    <div class="row">
                                        <span class="label">Mô tả:</span>
                                        <span class="value"><?php echo $mota; ?></span>
                                    </div>
                                    <div class="row">
                                        <span class="label">Số lượng:</span>
                                        <span class="value"><?php echo $so_luong_hien_thi; ?></span>
                                    </div>
                                </div>

                                <div class="options">
                                    <div class="row">
                                        <label class="label">Màu Sắc:</label>
                                        <div class="colors">
                                            <?php while ($mau = mysqli_fetch_assoc($result_mau)) { ?>
                                                <div class="color-item"><span><?php echo $mau['mau_name']; ?></span></div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="label">Size:</label>
                                        <div class="sizes">
                                            <?php while ($size = mysqli_fetch_assoc($result_size)) { ?>
                                                <button><?php echo $size['size_name']; ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="label">Số lượng:</label>
                                        <input type="number" name="so_luong" value="1" min="1" class="quantity-input">
                                    </div>
                                </div>

                                <div class="buttons">
                                    <?php if ($so_luong > 0) { ?>
                                        <form action="" method="POST">
                                            <input type="hidden" name="mahang" value="<?php echo $mahang; ?>">
                                            <input type="hidden" name="tenhang" value="<?php echo $tenhang; ?>">
                                            <input type="hidden" name="hinh" value="<?php echo $images[0]; ?>">
                                            <input type="hidden" name="gia" value="<?php echo ($don_gia - $giam_gia); ?>"> <!-- Giá sau giảm -->
                                            <input type="hidden" name="so_luong" value="1">
                                            <button type="submit" name="add_to_cart" class="add-to-cart"><i class="fa-solid fa-cart-shopping"></i> Thêm Vào Giỏ Hàng</button>
                                            <button type="submit" name="buy_now" class="buy-now">Mua Ngay</button>
                                        </form>
                                    <?php } else { ?>
                                        <button class="add-to-cart" disabled><i class="fa-solid fa-cart-shopping"></i> Hết Hàng</button>
                                        <button class="buy-now" disabled>Hết Hàng</button>
                                    <?php } ?>
                                </div>
                            </div>
                    <?php
                        } else {
                            echo "<div class='error-message'>Không tìm thấy sản phẩm.</div>";
                        }
                    } else {
                        echo "<div class='error-message'>Mã sản phẩm không được cung cấp.</div>";
                    }

                    // Đóng kết nối
                    mysqli_close($conn);
                    ?>
                </div>
            </div>
        </div>

        <script>
            let currentIndex = 0; // Chỉ số của ảnh hiện tại
            const thumbnailItems = document.querySelectorAll('.thumbnail');
            const mainImage = document.querySelector('.main-image');
            const maxVisible = 5; // Số lượng ảnh tối đa hiển thị

            function changeImage(thumbnail) {
                mainImage.src = thumbnail.src; // Thay đổi ảnh chính khi nhấp vào ảnh thu nhỏ
                currentIndex = Array.from(thumbnailItems).indexOf(thumbnail); // Cập nhật chỉ số hiện tại
                updateThumbnails();
            }

            function prevImage() {
                currentIndex = (currentIndex === 0) ? thumbnailItems.length - 1 : currentIndex - 1; // Nếu đến ảnh đầu tiên thì chuyển đến ảnh cuối
                updateThumbnails();
            }

            function nextImage() {
                currentIndex = (currentIndex === thumbnailItems.length - 1) ? 0 : currentIndex + 1; // Nếu đến ảnh cuối cùng thì chuyển về ảnh đầu
                updateThumbnails();
            }

            function updateThumbnails() {
                // Tính toán chỉ số bắt đầu và kết thúc cho 5 ảnh hiển thị
                let start = (currentIndex >= maxVisible) ? currentIndex - maxVisible + 1 : 0;

                // Kiểm tra nếu start nhỏ hơn 0 thì điều chỉnh để bắt đầu từ cuối
                if (start < 0) {
                    start = 0;
                }

                // Ẩn tất cả ảnh và hiển thị 5 ảnh hiện tại
                thumbnailItems.forEach((img, index) => {
                    img.style.display = 'none'; // Ẩn tất cả các ảnh
                });

                // Chạy vòng tròn để hiển thị 5 ảnh từ currentIndex
                for (let i = 0; i < maxVisible; i++) {
                    const currentDisplayIndex = (currentIndex + i) % thumbnailItems.length; // Chạy vòng tròn
                    thumbnailItems[currentDisplayIndex].style.display = 'block'; // Hiển thị ảnh
                }

                mainImage.src = thumbnailItems[currentIndex].src; // Cập nhật ảnh chính
            }

            // Khởi tạo: Chỉ hiển thị ảnh đầu tiên
            updateThumbnails(); // Gọi hàm để hiển thị ảnh đầu tiên khi tải trang

            // Xử lý khi chọn color
            document.querySelectorAll('.colors .color-item').forEach(function(colorItem) {
                colorItem.addEventListener('click', function() {
                    // Nếu mục đã được chọn (có class 'selected'), thì bỏ class 'selected'
                    if (colorItem.classList.contains('selected')) {
                        colorItem.classList.remove('selected');
                    } else {
                        // Bỏ class 'selected' khỏi tất cả các nút màu
                        document.querySelectorAll('.colors .color-item').forEach(function(item) {
                            item.classList.remove('selected');
                        });
                        // Thêm class 'selected' cho nút được chọn
                        colorItem.classList.add('selected');
                    }
                });
            });

            // Xử lý khi chọn size
            document.querySelectorAll('.sizes button').forEach(function(sizeButton) {
                sizeButton.addEventListener('click', function() {
                    // Nếu mục đã được chọn (có class 'selected'), thì bỏ class 'selected'
                    if (sizeButton.classList.contains('selected')) {
                        sizeButton.classList.remove('selected');
                    } else {
                        // Bỏ class 'selected' khỏi tất cả các nút size
                        document.querySelectorAll('.sizes button').forEach(function(button) {
                            button.classList.remove('selected');
                        });
                        // Thêm class 'selected' cho nút được chọn
                        sizeButton.classList.add('selected');
                    }
                });
            });
        </script>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>
