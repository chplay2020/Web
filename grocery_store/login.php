<?php

@include 'config.php';

session_start();

if (isset($_POST['submit'])) {

   $email = $_POST['email'];
   $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
   $input_pass = $_POST['pass'];

   // First, get user by email only
   $sql = "SELECT * FROM `users` WHERE email = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email]);
   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($row) {
      $stored_password = $row['password'];
      $password_valid = false;

      // kiem tra neu password la bcrypt (bat dau voi $2y$)
      if (strpos($stored_password, '$2y$') === 0) {
         // Verify bcrypt password
         $password_valid = password_verify($input_pass, $stored_password);
      } else {
         // kiem tra password la md5
         $md5_pass = md5($input_pass);
         $password_valid = ($stored_password === $md5_pass);
      }

      if ($password_valid) {
         if ($row['user_type'] == 'admin') {
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_page.php');
            exit();
         } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
            exit();
         } else {
            $message[] = 'Invalid user type!';
         }
      } else {
         $message[] = 'Incorrect email or password!';
      }
   } else {
      $message[] = 'No user found with this email!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/components.css">

</head>

<body>

   <?php

   if (isset($message)) {
      foreach ($message as $message) {
         echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
      }
   }

   ?>

   <section class="form-container">

      <form action="" method="POST">
         <h3>login now</h3>
         <input type="email" name="email" class="box" placeholder="enter your email" required>
         <input type="password" name="pass" class="box" placeholder="enter your password" required>
         <input type="submit" value="login now" class="btn" name="submit">
         <p>don't have an account? <a href="register.php">register now</a></p>
      </form>

   </section>


</body>

</html>