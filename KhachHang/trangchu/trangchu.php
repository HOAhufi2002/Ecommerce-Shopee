<?php
// Khởi động session để kiểm tra đăng nhập
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/base.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Document</title>
</head>


<body>
  <div class="app">
  <?php include 'header.php'; ?>

    <!-- Khung trắng riêng biệt -->
    <div class="A1B2C3">
      <div class="white-box">
        <!-- Slider nằm bên trong khung trắng -->
        <div class="slider">
          <div class="slides">
            <img src="../img/sp1.jpg" alt="Slide 1">
            <img src="../img/sp2.jpg" alt="Slide 2">
            <img src="../img/sp3.jpg" alt="Slide 3">
            <img src="../img/sp4.jpg" alt="Slide 4">
            <img src="../img/sp7.jpg" alt="Slide 4">
          </div>

          <!-- Nút chuyển ảnh -->
          <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
          <button class="next" onclick="moveSlide(1)">&#10095;</button>

          <!-- Chấm chỉ số ảnh -->
          <div class="dots">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
          </div>
        </div>
      </div>
    </div>

    <div class="app__container">
      <div class="grid">
        <div class="grid__row app__content">

          <div class="grid__colum-2">
            <nav class="category">
              <h3 class="category_heading">
                <i class="category_heading-icon fa-solid fa-list"></i>
                DANH MỤC
              </h3>

              <ul class="category-list">
                <li class="category-item category-item--active">
                  <a href="#" class="category-item__sp" onclick="return false;">Sản phẩm</a>
                </li>
                <?php
                include "connect.php";
                $sql = "SELECT * FROM loaihang";
                $kq = mysqli_query($conn, $sql);
                $n = mysqli_num_rows($kq);

                if ($n != 0) {
                  while ($row = mysqli_fetch_array($kq)) {
                    echo "<li class='category-item'>
                            <a href='?maloai=" . $row['maloai'] . "' class='category-item__link'>" . $row['tenloai'] . "</a>
                          </li>";
                  }
                }
                ?>
              </ul>
            </nav>
          </div>

          <!-- sản phẩm-->
          <div class="grid__colum-10">
            <div class="home-filter">
              <span class="home-filter__label">Sắp xếp theo</span>
              <button class="home-filter__btn btn">Phổ biến</button>
              <button class="home-filter__btn btn btn--primary">Mới nhất</button>
              <button class="home-filter__btn btn">Bán chạy</button>

              <div class="select-input">
    <span class="select-input__label">Giá</span>
    <i class="select-input__icon fas fa-angle-down"></i>

    <!-- Loc Gia-->
    <ul class="select-input__list">
        <li class="select-input__item">
            <a href="?sort=low_to_high" class="select-input__link">Giá: Thấp đến cao</a>
        </li>

        <li class="select-input__item">
            <a href="?sort=high_to_low" class="select-input__link">Giá: Cao đến thấp</a>
        </li>
    </ul>
</div>

              <?php
// Kiểm tra xem người dùng có chọn loại hàng hoặc sắp xếp giá không
$maloai = isset($_GET['maloai']) ? $_GET['maloai'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : null;

// Thiết lập số sản phẩm trên mỗi trang
$products_per_page = 20;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

// Xử lý truy vấn sắp xếp theo giá
$sort_query = "";
if ($sort == "low_to_high") {
    $sort_query = "ORDER BY hang.dongia ASC";
} elseif ($sort == "high_to_low") {
    $sort_query = "ORDER BY hang.dongia DESC";
}

// Truy vấn tổng số sản phẩm có lọc theo loại nếu có loại hàng
if ($maloai) {
    $total_query = "SELECT COUNT(*) AS total FROM hang WHERE maloai = '$maloai'";
} else {
    $total_query = "SELECT COUNT(*) AS total FROM hang";
}
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $products_per_page);

// Truy vấn để lấy sản phẩm có lọc theo loại và sắp xếp theo giá
if ($maloai) {
    $query = "SELECT hang.mahang, hang.tenhang, hang.mota, loaihang.tenloai, 
              SUBSTRING_INDEX(hang.hinhanh, ',', 1) AS hinh_dau, hang.giamgia, hang.dongia, hang.soluongton, 
              hang.thuonghieu, hang.quocgia 
              FROM hang 
              JOIN loaihang ON hang.maloai = loaihang.maloai 
              WHERE hang.maloai = '$maloai' 
              $sort_query
              LIMIT $offset, $products_per_page";
} else {
    $query = "SELECT hang.mahang, hang.tenhang, hang.mota, loaihang.tenloai, 
              SUBSTRING_INDEX(hang.hinhanh, ',', 1) AS hinh_dau, hang.giamgia, hang.dongia, hang.soluongton, 
              hang.thuonghieu, hang.quocgia 
              FROM hang 
              JOIN loaihang ON hang.maloai = loaihang.maloai 
              $sort_query
              LIMIT $offset, $products_per_page";
}
$kq = mysqli_query($conn, $query);

              // Mã hiển thị sản phẩm ở đây...

              // Mã phân trang
              ?>
              <div class="home-filter__page">
                <span class="home-filter__page-num">
                  <span class="home-filter__page-current"><?php echo $current_page; ?></span>/<span><?php echo $total_pages; ?></span>
                </span>

                <div class="home-filter__page-control">
                  <?php if ($current_page > 1): ?>
                    <a href="?page=<?php echo $current_page - 1; ?>" class="home-filter__page-btn">
                      <i class="home-filter__page-icon fas fa-angle-left"></i>
                    </a>
                  <?php else: ?>
                    <a href="#" class="home-filter__page-btn home-filter__page-btn--disabled">
                      <i class="home-filter__page-icon fas fa-angle-left"></i>
                    </a>
                  <?php endif; ?>

                  <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?php echo $current_page + 1; ?>" class="home-filter__page-btn">
                      <i class="home-filter__page-icon fas fa-angle-right"></i>
                    </a>
                  <?php else: ?>
                    <a href="#" class="home-filter__page-btn home-filter__page-btn--disabled">
                      <i class="home-filter__page-icon fas fa-angle-right"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <!-- Sản Phẩm -->
            <div class="home-product">
              <div class="grid__row">
              <?php
// Kiểm tra xem người dùng có chọn loại hàng nào không
$maloai = isset($_GET['maloai']) ? $_GET['maloai'] : null;

// Thiết lập số sản phẩm trên mỗi trang
$products_per_page = 20;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

// Truy vấn tổng số sản phẩm có lọc theo loại nếu có loại hàng
if ($maloai) {
  $total_query = "SELECT COUNT(*) AS total FROM hang WHERE maloai = '$maloai'";
} else {
  $total_query = "SELECT COUNT(*) AS total FROM hang";
}
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $products_per_page);

// Truy vấn để lấy sản phẩm có lọc theo loại nếu có loại hàng
if ($maloai) {
  $query = "SELECT hang.mahang, hang.tenhang, hang.mota, loaihang.tenloai, 
            SUBSTRING_INDEX(hang.hinhanh, ',', 1) AS hinh_dau, hang.giamgia, hang.dongia, hang.soluongton, 
            hang.thuonghieu, hang.quocgia 
            FROM hang 
            JOIN loaihang ON hang.maloai = loaihang.maloai 
            WHERE hang.maloai = '$maloai'
            LIMIT $offset, $products_per_page";
} else {
  $query = "SELECT hang.mahang, hang.tenhang, hang.mota, loaihang.tenloai, 
            SUBSTRING_INDEX(hang.hinhanh, ',', 1) AS hinh_dau, hang.giamgia, hang.dongia, hang.soluongton, 
            hang.thuonghieu, hang.quocgia 
            FROM hang 
            JOIN loaihang ON hang.maloai = loaihang.maloai 
            LIMIT $offset, $products_per_page";
}
$kq = mysqli_query($conn, $query);

                // Hiển thị từng sản phẩm
                while ($row = mysqli_fetch_array($kq)) {
                  $mahang = $row['mahang'];
                  $tenhang = $row['tenhang'];
                  $mota = $row['mota'];
                  $nhom = $row['tenloai'];
                  $hinh = $row['hinh_dau'];  // Hình ảnh đầu tiên
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

                  // Chuyển đổi số lượng hàng nếu trên 10.000 thành "10k"
                  if ($so_luong >= 10000) {
                    $so_luong_hien_thi = floor($so_luong / 1000) . 'k';
                  } else {
                    $so_luong_hien_thi = number_format($so_luong, 0, '.', '.');
                  }

                  // Giới hạn tên loại hàng (10 ký tự và thêm "...")
                  $nhom_hien_thi = (strlen($nhom) > 10) ? substr($nhom, 0, 10) . '...' : $nhom;

                  echo "
            <div class='grid__colum-2-4'>
                <a class='home-product-item' href='chitietsp.php?mah=$mahang'>
                    <div class='home-product-item__img' style='background-image: url(\"../img/$hinh\");'></div>
                    <h4 class='home-product-item__name'>$tenhang</h4>
                    <div class='home-product-item__price'>";

                  // Hiển thị giá gốc và giá sau khi giảm nếu có giảm giá
                  if ($giam_gia > 0) {
                    echo "<span class='home-product-item__price-old'>$gia_goc VNĐ</span>";
                    echo "<span class='home-product-item__price-current'>$gia_giam VNĐ</span>";
                  } else {
                    echo "<span class='home-product-item__price-current'>$gia_goc VNĐ</span>";
                  }

                  echo "
                    </div>
                    <div class='home-product-item__action'>";

                  // Kiểm tra số lượng hàng và hiển thị thông báo
                  if ($so_luong == 0) {
                    echo "<span class='home-product-item__like'><font color='red'>Hết hàng</font></span>";
                  } else {
                    echo "<span class='home-product-item__like'>Số lượng: $so_luong_hien_thi</span>";
                  }

                  // Hiển thị tên loại hàng
                  echo "
                    <div class='home-product-item__category'>
                        <font color='green'>$nhom_hien_thi</font>
                    </div>";

                  echo "
                    </div>
                    <div class='home-product-item__origin'>
                        <span class='home-product-item__brand'>$thuonghieu</span>
                        <span class='home-product-item__origin-name'>$quocgia</span>
                    </div>
                    <div class='home-product-item__favourite'>
                        <i class='fa-solid fa-check'></i>
                        <span>Yêu Thích</span>
                    </div>";

                  // Hiển thị % giảm giá nếu có giảm giá
                  if ($phan_tram_giam > 0) {
                    echo "
                    <div class='home-product-item__sale-off'>
                        <span class='home-product-item__sale-off-percent'>" . round($phan_tram_giam) . "%</span>
                        <span class='home-product-item__sale-off-label'>GIẢM</span>
                    </div>";
                  }

                  echo "
                </a>
            </div>";
                }
                mysqli_close($conn);
                ?>
              </div>
            </div>

          </div>

        </div>
        <!-- Thanh Phân Trang -->
        <ul class="pagination home-product__pagination">
          <?php if ($current_page > 1): ?>
            <li class="pagination-item">
              <a href="?page=<?php echo $current_page - 1; ?>" class="pagination-item__link">
                <i class="pagination-item__icon fas fa-angle-left"></i>
              </a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="pagination-item <?php echo ($i == $current_page) ? 'pagination-item--active' : ''; ?>">
              <a href="?page=<?php echo $i; ?>" class="pagination-item__link"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($current_page < $total_pages): ?>
            <li class="pagination-item">
              <a href="?page=<?php echo $current_page + 1; ?>" class="pagination-item__link">
                <i class="pagination-item__icon fas fa-angle-right"></i>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
  </div>
  </div>
  
  <?php include 'footer.php'; ?>


  </div>
  <script src="../css/script.js"></script>
</body>

</html><script>
  // Bắt sự kiện nhập liệu và nhấn Enter trong ô tìm kiếm
  document.getElementById('searchInput').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
      var input = this.value.toLowerCase(); // Chuyển từ khóa tìm kiếm về chữ thường
      var products = document.querySelectorAll('.grid__colum-2-4'); // Lấy tất cả các sản phẩm

      // Duyệt qua từng sản phẩm và kiểm tra tên sản phẩm
      products.forEach(function(product) {
        var productName = product.querySelector('.home-product-item__name').innerText.toLowerCase();

        // Kiểm tra xem tên sản phẩm có chứa từ khóa tìm kiếm không
        if (productName.includes(input)) {
          product.style.display = ''; // Hiển thị sản phẩm nếu khớp
        } else {
          product.style.display = 'none'; // Ẩn sản phẩm nếu không khớp
        }
      });
    }
  });
  // Bắt sự kiện nhập liệu trong ô tìm kiếm
  document.getElementById('searchInput').addEventListener('input', function() {
    var input = this.value.toLowerCase(); // Chuyển từ khóa tìm kiếm về chữ thường
    var products = document.querySelectorAll('.grid__colum-2-4'); // Lấy tất cả các sản phẩm

    // Duyệt qua từng sản phẩm và kiểm tra tên sản phẩm
    products.forEach(function(product) {
      var productName = product.querySelector('.home-product-item__name').innerText.toLowerCase();

      // Kiểm tra xem tên sản phẩm có chứa từ khóa tìm kiếm không
      if (productName.includes(input)) {
        product.style.display = ''; // Hiển thị sản phẩm nếu khớp
      } else {
        product.style.display = 'none'; // Ẩn sản phẩm nếu không khớp
      }
    });
  });
</script>