<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{
    /**
     * @test
     */
    public function example(): void
    {
        $psr4 = new AutoloadabilityInspectors(
            new AutoloadabilityInspector(
                'packages/validator/src',
                'Monorepo\\Component\\Validator\\'
            ),
            new AutoloadabilityInspector(
                'packages/validator/test/unit',
                'Test\\Unit\\Monorepo\\Component\\Validator\\'
            ),
            new AutoloadabilityInspector(
                'packages/validator/test/integration',
                'Test\\Integration\\Monorepo\\Component\\Validator\\'
            )
        );

        $validClassFile = new ClassFileUnderInspection(
            'packages/validator/src/Validator.php',
            'Monorepo\\Component\\Validator\\Validator'
        );

        $result = $psr4->inspect($validClassFile);
        self::assertTrue($result->isAutoloadable());

        $invalidClassFile = new ClassFileUnderInspection(
            'packages/validator/test/unit/ValidatorTest.php',
            'Monorepo\\Component\\Validator\\ValidatorTest'
        );

        /** @var NonAutoloadableClass $result */
        $result = $psr4->inspect($invalidClassFile);
        self::assertFalse($result->isAutoloadable());
        self::assertSame(
            'Monorepo\\Component\\Validator\\ValidatorTest',
            $result->getActualClassName()
        );
        self::assertSame(
            'Test\\Unit\\Monorepo\\Component\\Validator\\ValidatorTest',
            $result->getExpectedClassName()
        );
    }
}
