<?php

// Run before starting the local server. This file is outside the public folder.
if (PHP_SAPI !== 'cli') {
    exit('Run this check from the command line.');
}

$projectFolder = dirname(__DIR__);
$configurationFile = php_ini_loaded_file();
$problems = [];

echo 'PHP version: ' . PHP_VERSION . PHP_EOL;
echo 'PHP executable: ' . PHP_BINARY . PHP_EOL;
echo 'Loaded php.ini: ' . ($configurationFile ?: '(none)') . PHP_EOL;

if (version_compare(PHP_VERSION, '8.2.0', '<')) {
    $problems[] = 'Install PHP 8.2 or newer.';
}

foreach (['intl', 'mbstring', 'mysqli'] as $extension) {
    $loaded = extension_loaded($extension);
    echo $extension . ': ' . ($loaded ? 'loaded' : 'MISSING') . PHP_EOL;

    if (!$loaded) {
        $problems[] = 'Enable extension=' . $extension . ' in the php.ini shown above.';
    }
}

if (!is_file($projectFolder . '/vendor/autoload.php')) {
    $problems[] = 'The vendor folder is missing. Extract the entire ZIP or run composer install.';
}

// Test a real write. On Windows, the directory permission flag alone is not
// sufficient to explain whether PHP can create the files used by the app.
$runtimeFolders = ['writable', 'writable/cache', 'writable/logs',
    'writable/session', 'writable/uploads', 'writable/debugbar'];

foreach ($runtimeFolders as $relativeFolder) {
    $folder = $projectFolder . '/' . $relativeFolder;
    if (!is_dir($folder) && !@mkdir($folder, 0770, true) && !is_dir($folder)) {
        $problems[] = 'Cannot create folder: ' . $folder;
        continue;
    }

    $probe = $folder . '/.solar-write-check-' . bin2hex(random_bytes(8));
    $handle = @fopen($probe, 'x');
    if ($handle === false) {
        $problems[] = 'PHP cannot create a file in: ' . $folder;
        continue;
    }

    $written = fwrite($handle, 'Sun Son Solar write check');
    fclose($handle);
    $removed = @unlink($probe);
    if ($written !== strlen('Sun Son Solar write check') || !$removed) {
        $problems[] = 'PHP must be able to write and delete temporary files in: ' . $folder;
    } else {
        echo $relativeFolder . ': write test passed' . PHP_EOL;
    }
}
clearstatcache();

if ($problems !== []) {
    echo PHP_EOL . 'Please fix these items before starting:' . PHP_EOL;
    foreach ($problems as $problem) {
        echo '- ' . $problem . PHP_EOL;
    }
    exit(1);
}

echo PHP_EOL . 'Local requirements passed.' . PHP_EOL;
exit(0);
