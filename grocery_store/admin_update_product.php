<?php
// Kết nối file cấu hình database
@include 'config.php';

// Khởi tạo session để quản lý phiên đăng nhập
session_start();

// Lấy ID của admin từ session
$admin_id = $_SESSION['admin_id'];

// Kiểm tra xem admin đã đăng nhập chưa, nếu chưa thì chuyển về trang login
if (!isset($admin_id)) {
   header('location:login.php');
};

// Xử lý cập nhật sản phẩm khi form được submit
if (isset($_POST['update_product'])) {

   $pid = $_POST['pid']; // ID sản phẩm cần cập nhật

   // Lấy và bảo mật dữ liệu từ form
   $name = $_POST['name'];
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); // Tên sản phẩm

   $price = $_POST['price'];
   $price = htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); // Giá sản phẩm

   $category = $_POST['category'];
   $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); // Danh mục

   $details = $_POST['details'];
   $details = htmlspecialchars($details, ENT_QUOTES, 'UTF-8'); // Chi tiết sản phẩm

   // Xử lý ảnh upload
   $image = $_FILES['image']['name'];
   $image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
   $image_size = $_FILES['image']['size']; // Kích thước ảnh
   $image_tmp_name = $_FILES['image']['tmp_name']; // Đường dẫn tạm
   $image_folder = 'uploaded_img/' . $image; // Thư mục lưu ảnh
   $old_image = $_POST['old_image']; // Ảnh cũ

   // Cập nhật thông tin sản phẩm (không bao gồm ảnh)
   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $details, $price, $pid]);

   $message[] = 'product updated successfully!'; // Thông báo cập nhật thành công

   // Kiểm tra có ảnh mới được upload không
   if (!empty($image)) {
      // Kiểm tra kích thước ảnh (tối đa 2MB)
      if ($image_size > 2000000) {
         $message[] = 'image size is too large!';
      } else {

         // Cập nhật ảnh mới trong database
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);

         if ($update_image) {
            move_uploaded_file($image_tmp_name, $image_folder); // Lưu ảnh mới
            unlink('uploaded_img/' . $old_image); // Xóa ảnh cũ
            $message[] = 'image updated successfully!';
         }
      }
   }
} // Kết thúc xử lý cập nhật sản phẩm

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="update-product">

      <h1 class="title">update product</h1>

      <?php
      $update_id = $_GET['update']; // Lấy ID sản phẩm cần cập nhật từ URL

      // Truy vấn lấy thông tin sản phẩm cần cập nhật
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);

      // Kiểm tra có tìm thấy sản phẩm không
      if ($select_products->rowCount() > 0) {
         // Lặp qua kết quả và hiển thị form cập nhật
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <!-- Form cập nhật sản phẩm -->
            <form action="" method="post" enctype="multipart/form-data">
               <!-- Hidden input lưu ảnh cũ và ID sản phẩm -->
               <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">

               <!-- Hiển thị ảnh hiện tại -->
               <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">

               <!-- Các input để chỉnh sửa thông tin sản phẩm -->
               <input type="text" name="name" placeholder="enter product name" required class="box" value="<?= htmlspecialchars($fetch_products['name']); ?>">
               <input type="number" name="price" min="0" placeholder="enter product price" required class="box" value="<?= htmlspecialchars($fetch_products['price']); ?>">

               <!-- Dropdown chọn danh mục -->
               <select name="category" class="box" required>
                  <option selected><?= htmlspecialchars($fetch_products['category']); ?></option>
                  <option value="vegitables">vegitables</option>
                  <option value="fruits">fruits</option>
                  <option value="meat">meat</option>
                  <option value="fish">fish</option>
               </select>

               <!-- Textarea để nhập chi tiết sản phẩm -->
               <textarea name="details" required placeholder="enter product details" class="box" cols="30" rows="10"><?= htmlspecialchars($fetch_products['details']); ?></textarea>

               <!-- Input để upload ảnh mới (tùy chọn) -->
               <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">

               <div class="flex-btn">
                  <!-- Nút cập nhật sản phẩm -->
                  <input type="submit" class="btn" value="update product" name="update_product">
                  <!-- Nút quay lại trang quản lý sản phẩm -->
                  <a href="admin_products.php" class="option-btn">go back</a>
               </div>
            </form>
      <?php
         }
      } else {
         // Hiển thị thông báo nếu không tìm thấy sản phẩm
         echo '<p class="empty">no products found!</p>';
      }
      ?>

   </section>













   <script src="js/script.js"></script>

</body>

</html>