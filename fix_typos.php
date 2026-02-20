<?php
$files = [];
$dirs  = ['app/Http/Controllers', 'app/Services'];

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
    }
}

foreach ($files as $file) {
    $content    = file_get_contents($file);
    $newContent = preg_replace_callback(
        '/\$this->([A-Z][a-zA-Z0-9_]+Service)\s*=\s*\$([a-z][a-zA-Z0-9_]+Service);/',
        function ($matches) {
            if (lcfirst($matches[1]) === $matches[2]) {
                return str_replace('$' . $matches[2], '$' . $matches[1], $matches[0]);
            }
            return $matches[0];
        },
        $content
    );
    if ($content !== $newContent) {
        file_put_contents($file, $newContent);
        echo "Fixed " . $file . "\n";
    }
}
