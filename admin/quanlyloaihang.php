<?php
// Kết nối cơ sở dữ liệu
include('connect.php');

// Truy vấn danh sách loại hàng từ bảng 'loaihang'
$sql = "SELECT maloai, tenloai, nhasx FROM loaihang";
$result = mysqli_query($conn, $sql);

// Nội dung của trang quản lý loại hàng
$content = '<div class="container mt-4">';
$content .= '<h1 class="mb-4">Quản Lý Loại Hàng</h1>';
// Nút Thêm loại hàng (sử dụng modal)
$content .= '<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#themLoaiHangModal">Thêm Loại Hàng</button>';

$content .= '<table class="table table-hover table-bordered">';
$content .= '<thead class="table"><tr><th>Mã Loại</th><th>Tên Loại</th><th>Nhà Sản Xuất</th><th>Hành Động</th></tr></thead>';
$content .= '<tbody>';

// Lặp qua kết quả truy vấn và tạo nội dung bảng
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $content .= '<tr>';
        $content .= '<td>' . $row['maloai'] . '</td>';
        $content .= '<td>' . $row['tenloai'] . '</td>';
        $content .= '<td>' . $row['nhasx'] . '</td>';
        // Thêm nút sửa và xóa
        $content .= '<td>';
        $content .= '<button class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#suaLoaiHangModal" onclick="fillEditForm(\'' . $row['maloai'] . '\', \'' . $row['tenloai'] . '\', \'' . $row['nhasx'] . '\')">Sửa</button>';
        $content .= '<a href="xoa_loaihang.php?maloai=' . $row['maloai'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Bạn có chắc chắn muốn xóa loại hàng này không?\')">Xóa</a>';
        $content .= '</td>';
        $content .= '</tr>';
    }
} else {
    $content .= '<tr><td colspan="4">Không có loại hàng nào</td></tr>';
}

$content .= '</tbody></table>';
$content .= '</div>';

// Bao gồm layout
include('layout.php');

// Đóng kết nối
mysqli_close($conn);
?>

<!-- Modal Thêm Loại Hàng -->
<div class="modal fade" id="themLoaiHangModal" tabindex="-1" aria-labelledby="themLoaiHangModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="them_loaihang.php">
        <div class="modal-header">
          <h5 class="modal-title" id="themLoaiHangModalLabel">Thêm Loại Hàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="maloai" class="form-label">Mã Loại Hàng</label>
            <input type="text" class="form-control" id="maloai" name="maloai" required>
          </div>
          <div class="mb-3">
            <label for="tenloai" class="form-label">Tên Loại Hàng</label>
            <input type="text" class="form-control" id="tenloai" name="tenloai" required>
          </div>
          <div class="mb-3">
            <label for="nhasx" class="form-label">Nhà Sản Xuất</label>
            <input type="text" class="form-control" id="nhasx" name="nhasx" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
          <button type="submit" class="btn btn-primary">Thêm Loại Hàng</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Sửa Loại Hàng -->
<div class="modal fade" id="suaLoaiHangModal" tabindex="-1" aria-labelledby="suaLoaiHangModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="sua_loaihang.php">
        <div class="modal-header">
          <h5 class="modal-title" id="suaLoaiHangModalLabel">Sửa Loại Hàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="edit_maloai" name="maloai">
          <div class="mb-3">
            <label for="edit_tenloai" class="form-label">Tên Loại Hàng</label>
            <input type="text" class="form-control" id="edit_tenloai" name="tenloai" required>
          </div>
          <div class="mb-3">
            <label for="edit_nhasx" class="form-label">Nhà Sản Xuất</label>
            <input type="text" class="form-control" id="edit_nhasx" name="nhasx" required>
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
function fillEditForm(maloai, tenloai, nhasx) {
    document.getElementById('edit_maloai').value = maloai;
    document.getElementById('edit_tenloai').value = tenloai;
    document.getElementById('edit_nhasx').value = nhasx;
}
</script>
