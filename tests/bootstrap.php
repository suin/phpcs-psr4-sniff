<?php

declare(strict_types=1);

$files = [
    __DIR__ . '/../vendor/squizlabs/php_codesniffer/autoload.php',
    __DIR__ . '/../../../vendor/squizlabs/php_codesniffer/autoload.php',
];

foreach ($files as $file) {
    if (is_file($file)) {
        /** @noinspection PhpIncludeInspection */
        require $file;
        break;
    }
}
