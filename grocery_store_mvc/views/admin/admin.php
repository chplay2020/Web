<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

    <?php include 'views/layouts/admin_header.php'; ?>

    <section class="admin-accounts">

        <h1 class="title">admin accounts</h1>

        <div class="box-container">

            <?php
            if (!empty($users)) {
                $hasAdmin = false;
                foreach ($users as $user) {
                    if ($user['user_type'] === 'admin') {
                        $hasAdmin = true;
            ?>
                        <div class="box" style="<?php if ($user['id'] == $adminId) {
                                                    echo 'display:none';
                                                } ?>">
                            <img src="uploaded_img/<?= htmlspecialchars($user['image']); ?>" alt="">
                            <p> user id : <span><?= htmlspecialchars($user['id']); ?></span></p>
                            <p> username : <span><?= htmlspecialchars($user['name']); ?></span></p>
                            <p> email : <span><?= htmlspecialchars($user['email']); ?></span></p>
                            <p> user type : <span style="color:orange;">admin</span></p>
                            <a href="/admin_users?delete=<?= $user['id']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete</a>
                        </div>
            <?php
                    }
                }
                if (!$hasAdmin) {
                    echo '<p class="empty">no admin accounts found!</p>';
                }
            } else {
                echo '<p class="empty">no users found!</p>';
            }
            ?>
        </div>

    </section>

    <script src="js/script.js"></script>

</body>

</html>