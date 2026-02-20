<?php
$files = file('target_controllers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($files as $file) {
    if (! file_exists($file)) {
        continue;
    }

    $content  = file_get_contents($file);
    $modified = false;

    // 1. Dependency Injection Fix. Ensure PascalCase for protected properties
    if (preg_match_all('/protected\s+\\$([a-z][a-zA-Z0-9]*Service);/', $content, $matches)) {
        foreach ($matches[1] as $oldVar) {
            $newVar = ucfirst($oldVar);

            // declaration
            $content = preg_replace('/protected\s+\\$' . $oldVar . '\b/', 'protected $' . $newVar, $content);
            // parameter injection
            $content = preg_replace('/([a-zA-Z0-9_]+Service)\s+\\$' . $oldVar . '\b/', '$1 $' . $newVar, $content);
            // assignments and usages
            $content = preg_replace('/\\$this->' . $oldVar . '\b/', '$this->' . $newVar, $content);

            $modified = true;
        }
    }

    // 2. JSON Response Fixes (Common patterns)
    $jsonPatternSuccess = '/return\s+response\(\)->json\(\s*\[\s*\'success\'\s*=>\s*true\s*,\s*\'message\'\s*=>\s*([\'.\"]{1}.*?[\'.\"]{1})\s*\]\s*\);/s';
    if (preg_match($jsonPatternSuccess, $content)) {
        $content  = preg_replace($jsonPatternSuccess, 'return jsonSuccess($1);', $content);
        $modified = true;
    }

    $jsonPatternError = '/return\s+response\(\)->json\(\s*\[\s*\'success\'\s*=>\s*false\s*,\s*\'message\'\s*=>\s*([\'.\"]{1}.*?[\'.\"]{1})\s*\]\s*,\s*(500|400|404)\s*\);/s';
    if (preg_match($jsonPatternError, $content)) {
        $content  = preg_replace($jsonPatternError, 'return jsonError($1, $2);', $content);
        $modified = true;
    }

    $jsonPatternException = '/return\s+response\(\)->json\(\s*\[\s*\'success\'\s*=>\s*false\s*,\s*\'message\'\s*=>\s*(\$e->getMessage\(\))\s*\]\s*,\s*(500)\s*\);/s';
    if (preg_match($jsonPatternException, $content)) {
        $content  = preg_replace($jsonPatternException, 'return jsonError($1, 500);', $content);
        $modified = true;
    }

    if ($modified) {
        file_put_contents($file, $content);
        echo "Updated: $file\n";
    }
}
