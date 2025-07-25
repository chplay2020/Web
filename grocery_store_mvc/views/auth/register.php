<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>

<body>

   <?php
   if (isset($message)) {
      foreach ($message as $msg) {
         echo '
      <div class="message">
         <span>' . $msg . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      }
   }
   ?>

   <section class="form-container">

      <form action="" method="POST">
         <h3>register now</h3>
         <input type="text" name="name" class="box" placeholder="enter your name" required>
         <input type="email" name="email" class="box" placeholder="enter your email" required>
         <input type="password" name="pass" class="box" placeholder="enter your password" required>
         <input type="password" name="cpass" class="box" placeholder="confirm your password" required>
         <input type="submit" value="register now" class="btn" name="submit">
         <p>already have an account? <a href="login.php">login now</a></p>
      </form>

   </section>

</body>

</html>
