<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

   <!-- Import Font Awesome icons -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Import CSS cho admin -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'views/layouts/admin_header.php'; ?>

   <section class="dashboard">

      <h1 class="title">dashboard</h1>

      <div class="box-container">

         <div class="box">
            <h3>$<?= $totalPendings; ?>/-</h3>
            <p>total pendings</p>
            <a href="admin_orders.php" class="btn">see orders</a>
         </div>

         <div class="box">
            <h3>$<?= $totalCompleted; ?>/-</h3>
            <p>completed orders</p>
            <a href="admin_orders.php" class="btn">see orders</a>
         </div>

         <div class="box">
            <h3><?= $orderCount; ?></h3>
            <p>orders placed</p>
            <a href="admin_orders.php" class="btn">see orders</a>
         </div>

         <div class="box">
            <h3><?= $productCount; ?></h3>
            <p>products added</p>
            <a href="admin_products.php" class="btn">see products</a>
         </div>

         <div class="box">
            <h3><?= $regularUserCount; ?></h3>
            <p>total users</p>
            <a href="/admin_users?type=user" class="btn">See Accounts</a>
         </div>

         <div class="box">
            <h3><?= $adminCount; ?></h3>
            <p>total admins</p>
            <a href="/admin_users?type=admin" class="btn">See Accounts</a>
         </div>

         <div class="box">
            <h3><?= $userCount; ?></h3>
            <p>total accounts</p>
            <a href="/admin_users?type=all" class="btn">See Accounts</a>
         </div>

         <div class="box">
            <h3><?= $messageCount; ?></h3>
            <p>total messages</p>
            <a href="admin_contacts.php" class="btn">see messages</a>
         </div>

         <div class="box">
            <h3>🔄</h3>
            <p>database sync</p>
            <a href="sync_database.php" class="btn">sync database</a>
         </div>

      </div>

   </section>

   <script src="js/script.js"></script>

</body>

</html>