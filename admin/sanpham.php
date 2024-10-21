<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

// Truy vấn danh sách sản phẩm từ bảng 'hang'
$sql = "SELECT mahang, tenhang, dongia, soluongton, hinhanh, mota FROM hang";
$result = mysqli_query($conn, $sql);

// Nội dung của trang index - Hiển thị danh sách sản phẩm
$content = '<div class="container mt-4">';
$content .= '<h1 class="mb-4">Danh Sách Sản Phẩm</h1>';
// Nút Thêm sản phẩm (sử dụng modal)
$content .= '<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#themSanPhamModal">Thêm Sản Phẩm</button>';

$content .= '<table class="table table-hover table-bordered">';
$content .= '<thead class="table"><tr><th>Mã Hàng</th><th>Tên Hàng</th><th>Giá</th><th>Số Lượng Tồn</th><th>Hình Ảnh</th><th>Mô Tả</th><th>Hành Động</th></tr></thead>';
$content .= '<tbody>';

// Lặp qua kết quả truy vấn và tạo nội dung bảng
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $content .= '<tr>';
        $content .= '<td>' . $row['mahang'] . '</td>';
        $content .= '<td>' . $row['tenhang'] . '</td>';
        $content .= '<td>' . number_format($row['dongia']) . ' VND</td>';
        $content .= '<td>' . $row['soluongton'] . '</td>';
        $content .= '<td><img src="./img/' . $row['hinhanh'] . '" alt="' . $row['tenhang'] . '" style="width: 100px;"></td>';
        $content .= '<td>' . $row['mota'] . '</td>';
        // Thêm nút sửa và xóa (sử dụng modal)
        $content .= '<td>';
        $content .= '<button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#suaSanPhamModal" onclick="fillEditForm(\'' . $row['mahang'] . '\', \'' . $row['tenhang'] . '\', ' . $row['dongia'] . ', ' . $row['soluongton'] . ', \'' . $row['mota'] . '\')">Sửa</button>';
        $content .= '<a href="xoa_san_pham.php?mahang=' . $row['mahang'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa sản phẩm này không?\')">Xóa</a>';
        $content .= '</td>';
        $content .= '</tr>';
   
    }
} else {
    $content .= '<tr><td colspan="7">Không có sản phẩm nào</td></tr>';
}

$content .= '</tbody></table>';
$content .= '</div>';

// Bao gồm layout
include('layout.php');

// Đóng kết nối
mysqli_close($conn);
?>


<div class="modal fade" id="themSanPhamModal" tabindex="-1" aria-labelledby="themSanPhamModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="them_san_pham.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="themSanPhamModalLabel">Thêm Sản Phẩm</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="mahang" class="form-label">Mã Hàng</label>
            <input type="text" class="form-control" id="mahang" name="mahang" required>
          </div>
          <div class="mb-3">
            <label for="tenhang" class="form-label">Tên Hàng</label>
            <input type="text" class="form-control" id="tenhang" name="tenhang" required>
          </div>
          <div class="mb-3">
            <label for="dongia" class="form-label">Đơn Giá</label>
            <input type="number" class="form-control" id="dongia" name="dongia" required>
          </div>
          <div class="mb-3">
            <label for="soluongton" class="form-label">Số Lượng Tồn</label>
            <input type="number" class="form-control" id="soluongton" name="soluongton" required>
          </div>
          <div class="mb-3">
            <label for="hinhanh" class="form-label">Hình Ảnh</label>
            <input type="file" class="form-control" id="hinhanh" name="hinhanh" required>
          </div>
          <div class="mb-3">
            <label for="mota" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="mota" name="mota" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="suaSanPhamModal" tabindex="-1" aria-labelledby="suaSanPhamModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="sua_san_pham.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="suaSanPhamModalLabel">Sửa Sản Phẩm</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_mahang" name="mahang">
          <div class="mb-3">
            <label for="edit_tenhang" class="form-label">Tên Hàng</label>
            <input type="text" class="form-control" id="edit_tenhang" name="tenhang" required>
          </div>
          <div class="mb-3">
            <label for="edit_dongia" class="form-label">Đơn Giá</label>
            <input type="number" class="form-control" id="edit_dongia" name="dongia" required>
          </div>
          <div class="mb-3">
            <label for="edit_soluongton" class="form-label">Số Lượng Tồn</label>
            <input type="number" class="form-control" id="edit_soluongton" name="soluongton" required>
          </div>
          <div class="mb-3">
            <label for="edit_hinhanh" class="form-label">Hình Ảnh (Chọn nếu thay đổi)</label>
            <input type="file" class="form-control" id="edit_hinhanh" name="hinhanh">
            <input type="hidden" id="current_hinhanh" name="current_hinhanh">
          </div>
          <div class="mb-3">
            <label for="edit_mota" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="edit_mota" name="mota" rows="3" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- JavaScript để đổ dữ liệu vào form sửa -->
<script>
function fillEditForm(mahang, tenhang, dongia, soluongton, mota) {
    document.getElementById('edit_mahang').value = mahang;
    document.getElementById('edit_tenhang').value = tenhang;
    document.getElementById('edit_dongia').value = dongia;
    document.getElementById('edit_soluongton').value = soluongton;
    document.getElementById('edit_mota').value = mota;
}
</script>
