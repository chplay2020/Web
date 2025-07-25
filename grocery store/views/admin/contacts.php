<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'views/layouts/admin_header.php'; ?>

   <section class="messages">

      <h1 class="title">messages</h1>

      <div class="box-container">

         <?php
         if (!empty($messages)) {
            foreach ($messages as $message) {
         ?>
               <div class="box">
                  <p> user id : <span><?= htmlspecialchars($message['user_id']); ?></span> </p>
                  <p> name : <span><?= htmlspecialchars($message['name']); ?></span> </p>
                  <p> number : <span><?= htmlspecialchars($message['number']); ?></span> </p>
                  <p> email : <span><?= htmlspecialchars($message['email']); ?></span> </p>
                  <p> message : <span><?= htmlspecialchars($message['message']); ?></span> </p>
                  <a href="admin_contacts.php?delete=<?= $message['id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete</a>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">you have no messages!</p>';
         }
         ?>

      </div>

   </section>

   <script src="js/script.js"></script>

</body>

</html>
