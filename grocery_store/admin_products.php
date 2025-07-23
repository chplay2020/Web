<?php
// ========== TRANG QUẢN LÝ SẢN PHẨM - ADMIN ==========

@include 'config.php'; // Kết nối database

session_start(); // Khởi tạo session

$admin_id = $_SESSION['admin_id']; // Lấy admin_id từ session

// Kiểm tra quyền admin - chuyển về login nếu không phải admin
if (!isset($admin_id)) {
   header('location:login.php');
};

// ========== XỬ LÝ THÊM SẢN PHẨM MỚI ==========
if (isset($_POST['add_product'])) {

   // Lấy và escape thông tin sản phẩm từ form
   $name = $_POST['name'];
   $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
   $price = $_POST['price'];
   $price = htmlspecialchars($price, ENT_QUOTES, 'UTF-8');
   $category = $_POST['category']; // Danh mục sản phẩm
   $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
   $details = $_POST['details']; // Mô tả chi tiết
   $details = htmlspecialchars($details, ENT_QUOTES, 'UTF-8');

   // Xử lý upload ảnh sản phẩm
   $image = $_FILES['image']['name'];
   $image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   // Kiểm tra tên sản phẩm đã tồn tại chưa
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if ($select_products->rowCount() > 0) {
      $message[] = 'product name already exist!'; // Tên sản phẩm đã tồn tại
   } else {

      // Thêm sản phẩm mới vào database
      $insert_products = $conn->prepare("INSERT INTO `products`(name, category, details, price, image) VALUES(?,?,?,?,?)");
      $insert_products->execute([$name, $category, $details, $price, $image]);

      if ($insert_products) {
         if ($image_size > 2000000) { // Kiểm tra kích thước ảnh
            $message[] = 'image size is too large!';
         } else {
            move_uploaded_file($image_tmp_name, $image_folder); // Lưu ảnh vào thư mục uploaded_img
            $message[] = 'new product added!'; // Thông báo thêm sản phẩm thành công
         }
      }
   }
};

// Xử lý xóa sản phẩm khi có tham số delete trong URL
if (isset($_GET['delete'])) {

   $delete_id = $_GET['delete']; // Lấy ID sản phẩm cần xóa từ URL

   // Truy vấn lấy tên file ảnh của sản phẩm cần xóa
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);

   // Xóa file ảnh khỏi thư mục uploaded_img
   unlink('uploaded_img/' . $fetch_delete_image['image']);

   // Xóa sản phẩm khỏi bảng products
   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);

   // Xóa sản phẩm khỏi danh sách yêu thích của tất cả user
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);

   // Xóa sản phẩm khỏi giỏ hàng của tất cả user
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);

   // Chuyển hướng về trang admin_products.php sau khi xóa thành công
   header('location:admin_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="add-products">

      <h1 class="title">add new product</h1>

      <form action="" method="POST" enctype="multipart/form-data">
         <div class="flex">
            <div class="inputBox">
               <input type="text" name="name" class="box" required placeholder="enter product name">
               <select name="category" class="box" required>
                  <option value="" selected disabled>select category</option>
                  <option value="vegitables">vegitables</option>
                  <option value="fruits">fruits</option>
                  <option value="meat">meat</option>
                  <option value="fish">fish</option>
               </select>
            </div>
            <div class="inputBox">
               <input type="number" min="0" name="price" class="box" required placeholder="enter product price">
               <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
            </div>
         </div>
         <textarea name="details" class="box" required placeholder="enter product details" cols="30" rows="10"></textarea>
         <input type="submit" class="btn" value="add product" name="add_product">
      </form>

   </section>

   <section class="show-products">

      <h1 class="title">products added</h1>

      <div class="box-container">

         <?php
         // Truy vấn lấy tất cả sản phẩm từ database để hiển thị
         $show_products = $conn->prepare("SELECT * FROM `products`");
         $show_products->execute();

         // Kiểm tra có sản phẩm nào không
         if ($show_products->rowCount() > 0) {
            // Lặp qua từng sản phẩm và hiển thị
            while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="box">
                  <!-- Hiển thị giá sản phẩm với htmlspecialchars để bảo mật -->
                  <div class="price">$<?= htmlspecialchars($fetch_products['price']); ?>/-</div>
                  <!-- Hiển thị hình ảnh sản phẩm -->
                  <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
                  <!-- Hiển thị tên sản phẩm với htmlspecialchars để tránh XSS -->
                  <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
                  <!-- Hiển thị danh mục sản phẩm -->
                  <div class="cat"><?= htmlspecialchars($fetch_products['category']); ?></div>
                  <!-- Hiển thị chi tiết sản phẩm -->
                  <div class="details"><?= htmlspecialchars($fetch_products['details']); ?></div>
                  <div class="flex-btn">
                     <!-- Nút cập nhật sản phẩm - chuyển đến admin_update_product.php -->
                     <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
                     <!-- Nút xóa sản phẩm - có confirm để xác nhận trước khi xóa -->
                     <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
                  </div>
               </div>
         <?php
            }
         } else {
            // Hiển thị thông báo nếu chưa có sản phẩm nào
            echo '<p class="empty">now products added yet!</p>';
         }
         ?>

      </div>

   </section>











   <script src="js/script.js"></script>

</body>

</html>