<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php include 'views/layouts/header.php'; ?>

   <section class="placed-orders">

      <h1 class="title">placed orders</h1>

      <div class="box-container">

         <?php
         if (!empty($orders)) {
            foreach ($orders as $order) {
         ?>
               <div class="box">
                  <p> placed on : <span><?= $order['placed_on']; ?></span> </p>
                  <p> name : <span><?= $order['name']; ?></span> </p>
                  <p> number : <span><?= $order['number']; ?></span> </p>
                  <p> email : <span><?= $order['email']; ?></span> </p>
                  <p> address : <span><?= $order['address']; ?></span> </p>
                  <p> payment method : <span><?= $order['method']; ?></span> </p>
                  <p> your orders : <span><?= $order['total_products']; ?></span> </p>
                  <p> total price : <span>$<?= $order['total_price']; ?>/-</span> </p>
                  <p> payment status : <span style="color:<?php if ($order['payment_status'] == 'pending') { echo 'red'; } else { echo 'green'; } ?>"><?= $order['payment_status']; ?></span> </p>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">no orders placed yet!</p>';
         }
         ?>

      </div>

   </section>

   <?php include 'views/layouts/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>
