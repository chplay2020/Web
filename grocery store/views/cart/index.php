<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- Import Font Awesome icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Import CSS -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'views/layouts/header.php'; ?>

   <!-- ========== SECTION HIỂN THỊ GIỎ HÀNG ========== -->
   <section class="shopping-cart">

      <h1 class="title">products added</h1>

      <div class="box-container">

         <?php
         if (!empty($cartItems)) {
            foreach ($cartItems as $item) {
               $subTotal = $item['price'] * $item['quantity'];
         ?>
               <form action="" method="POST" class="box">
                  <a href="/cart?delete=<?= $item['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
                  <a href="/product?id=<?= $item['pid']; ?>" class="fas fa-eye"></a>
                  <img src="uploaded_img/<?= $item['image']; ?>" alt="">
                  <div class="name"><?= $item['name']; ?></div>
                  <div class="price">$<?= $item['price']; ?>/-</div>
                  <input type="hidden" name="cart_id" value="<?= $item['id']; ?>">
                  <div class="flex-btn">
                     <input type="number" min="1" value="<?= $item['quantity']; ?>" class="qty" name="p_qty">
                     <input type="submit" value="update" name="update_qty" class="option-btn">
                  </div>
                  <div class="sub-total"> sub total : <span>$<?= $subTotal; ?>/-</span> </div>
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">your cart is empty</p>';
         }
         ?>
      </div>

      <!-- ========== PHẦN TỔNG TIỀN VÀ CÁC HÀNH ĐỘNG ========== -->
      <div class="cart-total">
         <p>grand total : <span>$<?= $grandTotal; ?>/-</span></p>
         <a href="/shop" class="option-btn">continue shopping</a>
         <a href="/cart?delete_all" class="delete-btn <?= ($grandTotal > 1) ? '' : 'disabled'; ?>">delete all</a>
         <a href="/checkout" class="btn <?= ($grandTotal > 1) ? '' : 'disabled'; ?>">proceed to checkout</a>
      </div>

   </section>

   <?php include 'views/layouts/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>
