<?php
$directory = 'resources/views';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$with_id = [];
$without_id = [];

foreach ($it as $file) {
    if ($file->isDir() || $file->getExtension() !== 'php') continue;
    
    $content = file_get_contents($file->getPathname());
    if (preg_match_all('/<x-tabler\.form-modal\b([^>]*)>/is', $content, $matches)) {
        foreach ($matches[1] as $attributes) {
            $path = str_replace(realpath($directory) . DIRECTORY_SEPARATOR, '', realpath($file->getPathname()));
            if (preg_match('/\bid\s*=/is', $attributes)) {
                $with_id[] = $path;
            } else {
                $without_id[] = $path;
            }
        }
    }
}

$with_id = array_unique($with_id);
$without_id = array_unique($without_id);

echo "START_DATA\n";
echo "WITH_ID_TOTAL:" . count($with_id) . "\n";
echo "WITHOUT_ID_TOTAL:" . count($without_id) . "\n";
echo "WITH_ID_SAMPLES:" . implode('|', array_slice($with_id, 0, 10)) . "\n";
echo "WITHOUT_ID_SAMPLES:" . implode('|', array_slice($without_id, 0, 10)) . "\n";
echo "END_DATA\n";
