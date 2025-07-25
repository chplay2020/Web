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
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'views/layouts/admin_header.php'; ?>

   <section class="placed-orders">

      <h1 class="title">placed orders</h1>

      <div class="box-container">

         <?php
         if (!empty($orders)) {
            foreach ($orders as $order) {
         ?>
               <div class="box">
                  <p> user id : <span><?= htmlspecialchars($order['user_id']); ?></span> </p>
                  <p> placed on : <span><?= htmlspecialchars($order['placed_on']); ?></span> </p>
                  <p> name : <span><?= htmlspecialchars($order['name']); ?></span> </p>
                  <p> email : <span><?= htmlspecialchars($order['email']); ?></span> </p>
                  <p> number : <span><?= htmlspecialchars($order['number']); ?></span> </p>
                  <p> address : <span><?= htmlspecialchars($order['address']); ?></span> </p>
                  <p> total products : <span><?= htmlspecialchars($order['total_products']); ?></span> </p>
                  <p> total price : <span>$<?= htmlspecialchars($order['total_price']); ?>/-</span> </p>
                  <p> payment method : <span><?= htmlspecialchars($order['method']); ?></span> </p>

                  <form action="" method="POST">
                     <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                     <select name="update_payment" class="drop-down">
                        <option value="" selected disabled><?= htmlspecialchars($order['payment_status']); ?></option>
                        <option value="pending">pending</option>
                        <option value="completed">completed</option>
                     </select>
                     <div class="flex-btn">
                     <input type="submit" name="update_order" class="option-btn" value="update">
                     <a href="/admin_orders?delete=<?= $order['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
                     </div>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">no orders placed yet!</p>';
         }
         ?>

      </div>

   </section>

   <script src="js/script.js"></script>

</body>

</html>
