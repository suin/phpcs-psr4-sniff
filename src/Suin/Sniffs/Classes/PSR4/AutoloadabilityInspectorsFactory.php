<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

use InvalidArgumentException;
use RuntimeException;

final class AutoloadabilityInspectorsFactory
{
    public static function create(
        ?string $basePath,
        string $composerJsonPath
    ): AutoloadabilityInspectors {
        $resolvedComposerJsonPath = self::resolveComposerJsonPath(
            $basePath,
            $composerJsonPath
        );
        self::assertFileExists($resolvedComposerJsonPath);
        self::assertFileIsReadable($resolvedComposerJsonPath);
        return self::getPsr4Directories($resolvedComposerJsonPath);
    }

    private static function getPsr4Directories(
        string $filename
    ): AutoloadabilityInspectors {
        $contents = \file_get_contents($filename);

        if ($contents === false) {
            throw new RuntimeException("Unable to read file: {$filename}");
        }
        $data = \json_decode($contents, true);

        if (!\is_array($data)) {
            throw new RuntimeException(
                "Unable to decode json: {$filename}"
            );
        }
        $psr4Directories = [];

        if (isset($data['autoload']['psr-4'])) {
            foreach ($data['autoload']['psr-4'] as $namespace => $dirs) {
                if (!is_array($dirs)) {
                    $dirs = [$dirs];
                }
                foreach ($dirs as $dir) {
                    $psr4Directories[] = new AutoloadabilityInspector(
                        \dirname($filename) . '/' . $dir,
                        $namespace
                    );
                }
            }
        }

        if (isset($data['autoload-dev']['psr-4'])) {
            foreach ($data['autoload-dev']['psr-4'] as $namespace => $dirs) {
                if (!is_array($dirs)) {
                    $dirs = [$dirs];
                }
                foreach ($dirs as $dir) {
                    $psr4Directories[] = new AutoloadabilityInspector(
                        \dirname($filename) . '/' . $dir,
                        $namespace
                    );
                }
            }
        }
        return new AutoloadabilityInspectors(...$psr4Directories);
    }

    private static function resolveComposerJsonPath(
        ?string $basePath,
        string $composerJsonPath
    ): string {
        return $basePath === null ?
            $composerJsonPath :
            $basePath . '/' . $composerJsonPath;
    }

    private static function assertFileExists(string $filename): void
    {
        if (!\is_file($filename)) {
            throw new InvalidArgumentException(
                "composer.json file not found: {$filename}"
            );
        }
    }

    private static function assertFileIsReadable(string $filename): void
    {
        if (!\is_readable($filename)) {
            throw new InvalidArgumentException(
                "composer.json file is not readable: {$filename}"
            );
        }
    }
}
