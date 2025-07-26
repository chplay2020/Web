<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>wishlist</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'views/layouts/header.php'; ?>

   <section class="wishlist">

      <h1 class="title">products added</h1>

      <div class="box-container">

         <?php
         if (!empty($wishlistItems)) {
            foreach ($wishlistItems as $item) {
         ?>
               <form action="" method="POST" class="box">
                  <a href="wishlist.php?delete=<?= $item['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from wishlist?');"></a>
                  <a href="view_page.php?pid=<?= $item['pid']; ?>" class="fas fa-eye"></a>
                  <img src="uploaded_img/<?= $item['image']; ?>" alt="">
                  <div class="name"><?= $item['name']; ?></div>
                  <div class="price">$<?= $item['price']; ?>/-</div>
                  <input type="number" min="1" value="1" class="qty" name="p_qty">
                  <input type="hidden" name="pid" value="<?= $item['pid']; ?>">
                  <input type="hidden" name="p_name" value="<?= $item['name']; ?>">
                  <input type="hidden" name="p_price" value="<?= $item['price']; ?>">
                  <input type="hidden" name="p_image" value="<?= $item['image']; ?>">
                  <input type="submit" value="add to cart" name="add_to_cart" class="btn">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">your wishlist is empty</p>';
         }
         ?>
      </div>

      <div class="wishlist-total">
         <p>grand total : <span>$<?= $grandTotal; ?>/-</span></p>
         <a href="shop.php" class="option-btn">continue shopping</a>
         <a href="wishlist.php?delete_all" class="delete-btn <?= ($grandTotal > 1) ? '' : 'disabled'; ?>">delete all</a>
      </div>

   </section>

   <?php include 'views/layouts/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>
