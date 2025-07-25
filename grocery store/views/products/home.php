<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>

   <!-- Import Font Awesome icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Import CSS cho trang chủ -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'views/layouts/header.php'; ?>

   <!-- ========== SECTION BANNER TRANG CHỦ ========== -->
   <div class="home-bg">

      <section class="home">

         <div class="content">
            <span>don't panic, go organice</span>
            <h3>Reach For A Healthier You With Organic Foods</h3>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto natus culpa officia quasi, accusantium explicabo?</p>
            <a href="about.php" class="btn">about us</a>
         </div>

      </section>

   </div>

   <!-- ========== SECTION DANH MỤC SẢN PHẨM ========== -->
   <section class="home-category">

      <h1 class="title">shop by category</h1>

      <div class="box-container">

         <!-- Box danh mục Fruits -->
         <div class="box">
            <img src="images/cat-1.png" alt="">
            <h3>fruits</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
            <a href="category.php?category=fruits" class="btn">fruits</a>
         </div>

         <!-- Box danh mục Meat -->
         <div class="box">
            <img src="images/cat-2.png" alt="">
            <h3>meat</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
            <a href="category.php?category=meat" class="btn">meat</a>
         </div>

         <!-- Box danh mục Vegetables -->
         <div class="box">
            <img src="images/cat-3.png" alt="">
            <h3>vegitables</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
            <a href="category.php?category=vegitables" class="btn">vegitables</a>
         </div>

         <!-- Box danh mục Fish -->
         <div class="box">
            <img src="images/cat-4.png" alt="">
            <h3>fish</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p>
            <a href="category.php?category=fish" class="btn">fish</a>
         </div>

      </div>

   </section>

   <!-- ========== SECTION SẢN PHẨM MỚI NHẤT ========== -->
   <section class="products">

      <h1 class="title">latest products</h1>

      <div class="box-container">

         <?php
         if (!empty($products)) {
            foreach ($products as $product) {
         ?>
               <form action="" class="box" method="POST">
                  <div class="price">$<span><?= $product['price']; ?></span>/-</div>
                  <a href="view_page.php?pid=<?= $product['id']; ?>" class="fas fa-eye"></a>
                  <img src="uploaded_img/<?= $product['image']; ?>" alt="">
                  <div class="name"><?= $product['name']; ?></div>

                  <input type="hidden" name="pid" value="<?= $product['id']; ?>">
                  <input type="hidden" name="p_name" value="<?= $product['name']; ?>">
                  <input type="hidden" name="p_price" value="<?= $product['price']; ?>">
                  <input type="hidden" name="p_image" value="<?= $product['image']; ?>">

                  <input type="number" min="1" value="1" name="p_qty" class="qty">

                  <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">

                  <input type="submit" value="add to cart" class="btn" name="add_to_cart">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">no products added yet!</p>';
         }
         ?>

      </div>

   </section>

   <?php include 'views/layouts/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>
