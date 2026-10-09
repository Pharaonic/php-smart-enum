<?php

declare(strict_types=1);

use Pharaonic\SmartEnum\Tools\EnumDocBlockGenerator;

require dirname(__DIR__) . '/vendor/autoload.php';

$dryRun = in_array('--dry-run', $argv, true);
$projectPath = dirname(__DIR__);

foreach (array_slice($argv, 1) as $argument) {
    if (str_starts_with($argument, '-')) {
        continue;
    }

    $projectPath = $argument;
    break;
}

if (!is_dir($projectPath)) {
    fwrite(STDERR, "[ERROR] Directory not found: {$projectPath}\n");
    exit(1);
}

$resolvedPath = realpath($projectPath);

if ($resolvedPath === false) {
    fwrite(STDERR, "[ERROR] Directory not found: {$projectPath}\n");
    exit(1);
}

$projectPath = $resolvedPath;
$generator = EnumDocBlockGenerator::create();

$enumsScanned = 0;
$updatedFiles = 0;
$errors = 0;

/**
 * Decide whether a directory should be excluded.
 */
function shouldSkipDirectory(string $directoryPath, string $projectPath): bool
{
    $relativePath = str_replace(
        '\\',
        '/',
        substr($directoryPath, strlen($projectPath) + 1),
    );

    $segments = explode('/', $relativePath);

    foreach ($segments as $segment) {
        if (in_array($segment, ['vendor', '.git', 'node_modules', 'storage'], true)) {
            return true;
        }
    }

    return $relativePath === 'bootstrap/cache'
        || str_starts_with($relativePath, 'bootstrap/cache/');
}

$directoryIterator = new RecursiveDirectoryIterator(
    $projectPath,
    FilesystemIterator::SKIP_DOTS,
);

$filter = new RecursiveCallbackFilterIterator(
    $directoryIterator,
    static function (SplFileInfo $current) use ($projectPath): bool {
        if (!$current->isDir()) {
            return true;
        }

        return !shouldSkipDirectory($current->getPathname(), $projectPath);
    },
);

$iterator = new RecursiveIteratorIterator($filter);

foreach ($iterator as $file) {
    if (!$file instanceof SplFileInfo) {
        continue;
    }

    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $filePath = $file->getPathname();
    $contents = file_get_contents($filePath);

    if ($contents === false) {
        $errors++;

        fwrite(STDERR, "[ERROR] Could not read: {$filePath}\n");

        continue;
    }

    $result = $generator->process($contents);

    if ($result['error'] !== null) {
        $errors++;

        fwrite(STDERR, "[ERROR] {$filePath}: {$result['error']}\n");

        continue;
    }

    $enumsScanned += $result['enums'];

    if (!$result['changed']) {
        continue;
    }

    if ($dryRun) {
        echo "[DRY-RUN] {$filePath}\n";
        $updatedFiles++;

        continue;
    }

    $written = file_put_contents($filePath, $result['contents']);

    if ($written === false) {
        $errors++;

        fwrite(STDERR, "[ERROR] Could not write: {$filePath}\n");

        continue;
    }

    echo "[UPDATE] {$filePath}\n";

    $updatedFiles++;
}

echo "\n";
echo "Enums scanned: {$enumsScanned}\n";
echo "Files needing updates: {$updatedFiles}\n";
echo "Errors: {$errors}\n";
