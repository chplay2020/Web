<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>

<body>

   <?php include 'views/layouts/admin_header.php'; ?>

   <section class="update-product">

      <h1 class="title">update product</h1>

      <?php if ($product): ?>
         <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_image" value="<?= $product['image']; ?>">
            <input type="hidden" name="pid" value="<?= $product['id']; ?>">

            <img src="uploaded_img/<?= htmlspecialchars($product['image']); ?>" alt="">

            <input type="text" name="name" placeholder="enter product name" required class="box" value="<?= htmlspecialchars($product['name']); ?>">
            <input type="number" name="price" min="0" placeholder="enter product price" required class="box" value="<?= htmlspecialchars($product['price']); ?>">

            <select name="category" class="box" required>
               <option selected><?= htmlspecialchars($product['category']); ?></option>
               <option value="vegitables">vegitables</option>
               <option value="fruits">fruits</option>
               <option value="meat">meat</option>
               <option value="fish">fish</option>
            </select>

            <textarea name="details" required placeholder="enter product details" class="box" cols="30" rows="10"><?= htmlspecialchars($product['details']); ?></textarea>

            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">

            <div class="flex-btn">
               <input type="submit" class="btn" value="update product" name="update_product">
               <a href="admin_products.php" class="option-btn">go back</a>
            </div>
         </form>
      <?php else: ?>
         <p class="empty">no products found!</p>
      <?php endif; ?>

   </section>

   <script src="js/script.js"></script>

</body>

</html>
