$files = Get-ChildItem -Path "e:\code\WebNC\grocery store\grocery store" -Filter "*.php"

foreach ($file in $files) {
    $content = Get-Content $file.FullName -Raw
    
    # Fix common variable patterns
    $content = $content -replace 'htmlspecialchars\(\$email, ENT_QUOTES, ''UTF-8''\)', 'htmlspecialchars($pid, ENT_QUOTES, ''UTF-8'')'
    $content = $content -replace '\$pid = htmlspecialchars\(\$pid, ENT_QUOTES, ''UTF-8''\);', '$pid = htmlspecialchars($pid, ENT_QUOTES, ''UTF-8'');'
    
    # Fix specific patterns for each variable
    $lines = $content -split "`n"
    
    for ($i = 0; $i -lt $lines.Length; $i++) {
        if ($lines[$i] -match '\$(\w+) = \$_POST\[''(\w+)''\];') {
            $varName = $matches[1]
            $postKey = $matches[2]
            if ($i + 1 -lt $lines.Length -and $lines[$i + 1] -match 'htmlspecialchars\(\$\w+, ENT_QUOTES, ''UTF-8''\)') {
                $lines[$i + 1] = "   `$$varName = htmlspecialchars(`$$varName, ENT_QUOTES, 'UTF-8');"
            }
        }
    }
    
    $content = $lines -join "`n"
    Set-Content -Path $file.FullName -Value $content
}
