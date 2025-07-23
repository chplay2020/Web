# Fix specific variable patterns in files

# Fix search_page.php
$content = Get-Content "search_page.php" -Raw
$content = $content -replace '\$search_box = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$search_box = htmlspecialchars($search_box, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$pid = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$pid = htmlspecialchars($pid, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_name = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_name = htmlspecialchars($p_name, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_price = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_price = htmlspecialchars($p_price, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_image = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_image = htmlspecialchars($p_image, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_qty = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_qty = htmlspecialchars($p_qty, ENT_QUOTES, ''UTF-8'');'
Set-Content "search_page.php" -Value $content

# Fix wishlist.php
$content = Get-Content "wishlist.php" -Raw
$content = $content -replace '\$pid = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$pid = htmlspecialchars($pid, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_name = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_name = htmlspecialchars($p_name, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_price = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_price = htmlspecialchars($p_price, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_image = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_image = htmlspecialchars($p_image, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_qty = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_qty = htmlspecialchars($p_qty, ENT_QUOTES, ''UTF-8'');'
Set-Content "wishlist.php" -Value $content

# Fix category.php
$content = Get-Content "category.php" -Raw
$content = $content -replace '\$pid = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$pid = htmlspecialchars($pid, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_name = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_name = htmlspecialchars($p_name, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_price = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_price = htmlspecialchars($p_price, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_image = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_image = htmlspecialchars($p_image, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$p_qty = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_qty = htmlspecialchars($p_qty, ENT_QUOTES, ''UTF-8'');'
Set-Content "category.php" -Value $content

# Fix cart.php
$content = Get-Content "cart.php" -Raw
$content = $content -replace '\$p_qty = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$p_qty = htmlspecialchars($p_qty, ENT_QUOTES, ''UTF-8'');'
Set-Content "cart.php" -Value $content

# Fix contact.php
$content = Get-Content "contact.php" -Raw
$content = $content -replace '\$name = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$name = htmlspecialchars($name, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$email = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$email = htmlspecialchars($email, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$number = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$number = htmlspecialchars($number, ENT_QUOTES, ''UTF-8'');'
$content = $content -replace '\$msg = htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\);', '$msg = htmlspecialchars($msg, ENT_QUOTES, ''UTF-8'');'
Set-Content "contact.php" -Value $content

Write-Host "Fixed all variable patterns in PHP files"
