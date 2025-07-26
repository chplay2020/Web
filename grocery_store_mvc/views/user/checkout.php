<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- Import Font Awesome icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Import CSS -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'views/layouts/header.php'; ?>

   <!-- ========== SECTION HIỂN THỊ ĐƠN HÀNG ========== -->
   <section class="display-orders">

      <?php
      if (!empty($cartItems)) {
         foreach ($cartItems as $item) {
            $cartTotalPrice = ($item['price'] * $item['quantity']);
      ?>
            <p> <?= $item['name']; ?> <span>(<?= '$' . $item['price'] . '/- x ' . $item['quantity']; ?>)</span> </p>
      <?php
         }
      } else {
         echo '<p class="empty">your cart is empty!</p>';
      }
      ?>
      <div class="grand-total">grand total : <span>$<?= $cartGrandTotal; ?>/-</span></div>
   </section>

   <section class="checkout-orders">

      <form action="" method="POST">

         <h3>place your order</h3>

         <div class="flex">
            <div class="inputBox">
               <span>your name :</span>
               <input type="text" name="name" placeholder="enter your name" class="box" required>
            </div>
            <div class="inputBox">
               <span>your number :</span>
               <input type="number" name="number" placeholder="enter your number" class="box" required>
            </div>
            <div class="inputBox">
               <span>your email :</span>
               <input type="email" name="email" placeholder="enter your email" class="box" required>
            </div>
            <div class="inputBox">
               <span>payment method :</span>
               <select name="method" class="box" required>
                  <option value="cash on delivery">cash on delivery</option>
                  <option value="credit card">credit card</option>
                  <option value="paytm">paytm</option>
                  <option value="paypal">paypal</option>
               </select>
            </div>
            <div class="inputBox">
               <span>address line 01 :</span>
               <input type="text" name="flat" placeholder="e.g. flat number" class="box" required>
            </div>
            <div class="inputBox">
               <span>address line 02 :</span>
               <input type="text" name="street" placeholder="e.g. street name" class="box" required>
            </div>
            <div class="inputBox">
               <span>city :</span>
               <input type="text" name="city" placeholder="e.g. mumbai" class="box" required>
            </div>
            <div class="inputBox">
               <span>state :</span>
               <input type="text" name="state" placeholder="e.g. maharashtra" class="box" required>
            </div>
            <div class="inputBox">
               <span>country :</span>
               <input type="text" name="country" placeholder="e.g. India" class="box" required>
            </div>
            <div class="inputBox">
               <span>pin code :</span>
               <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" class="box" required>
            </div>
         </div>

         <input type="submit" name="order" class="btn <?= ($cartGrandTotal > 1) ? '' : 'disabled'; ?>" value="place order">

      </form>

   </section>

   <?php include 'views/layouts/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>
