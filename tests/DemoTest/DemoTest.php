<?php

/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

namespace Suin\Sniffs\Classes\DemoTest;

use SlevomatCodingStandard\Sniffs\TestCase;
use Suin\Sniffs\Classes\PSR4Sniff;

final class DemoTest extends TestCase
{
    /**
     * @test
     */
    public function runDemo(): void
    {
        $report = self::checkFile(
            __DIR__ . '/../demo/component2/src/MissingNamespaceClass.php',
            [
                'composerJsonPath' => __DIR__ . '/../demo/composer.json',
            ]
        );
        self::assertSame(1, $report->getErrorCount());
        self::assertSniffError(
            $report,
            3,
            PSR4Sniff::CODE_INCORRECT_CLASS_NAME,
            'Class name is not compliant with PSR-4 configuration. It should ' .
            'be `Demo\Component2\MissingNamespaceClass` instead of ' .
            '`MissingNamespaceClass`.'
        );
    }

    /** @noinspection PhpMissingParentCallCommonInspection */
    protected static function getSniffClassName(): string
    {
        return PSR4Sniff::class;
    }
}
