<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use Suin\Sniffs\Classes\PSR4\AutoloadabilityInspectors;
use Suin\Sniffs\Classes\PSR4\AutoloadabilityInspectorsFactory;
use Suin\Sniffs\Classes\PSR4\ClassFileUnderInspection;
use Suin\Sniffs\Classes\PSR4\NonAutoloadableClass;

final class PSR4Sniff implements Sniff
{
    public const CODE_INCORRECT_CLASS_NAME = 'IncorrectClassName';

    private const INITIALIZED = 1;

    private const UNINITIALIZED = 0;

    private const INITIALIZATION_FAILURE = -1;

    /**
     * File path of "composer.json".
     *
     * This must be relative path to "--basepath" option of phpcs command.
     *
     * @var null|string
     */
    public $composerJsonPath = 'composer.json';

    /**
     * @var AutoloadabilityInspectors
     */
    private $autoloadabilityInspectors;

    /**
     * @var int
     */
    private $initialization = self::UNINITIALIZED;

    /**
     * {@inheritdoc}
     */
    public function register(): array
    {
        return [\T_CLASS, \T_INTERFACE, \T_TRAIT];
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $phpcsFile, $typePointer): void
    {
        $this->initializeThisSniffIfNotYet($phpcsFile->config);

        if ($this->initialization === self::INITIALIZATION_FAILURE) {
            return;
        }

        $classFile = $this->getClassFileOf($phpcsFile, $typePointer);
        $result = $this->autoloadabilityInspectors->inspect($classFile);

        if ($result instanceof NonAutoloadableClass) {
            $this->addError($phpcsFile, $result, $typePointer);
        }
    }

    private function initializeThisSniffIfNotYet(Config $config): void
    {
        if ($this->initialization === self::UNINITIALIZED) {
            $this->initialization = self::INITIALIZATION_FAILURE;
            $this->autoloadabilityInspectors =
                AutoloadabilityInspectorsFactory::create(
                    $config->getSettings()['basepath'],
                    $this->composerJsonPath
                );
            $this->initialization = self::INITIALIZED;
        }
    }

    private function addError(
        File $phpcsFile,
        NonAutoloadableClass $result,
        int $typePointer
    ): void {
        $phpcsFile->addError(
            \sprintf(
                'Class name is not compliant with PSR-4 configuration. ' .
                'It should be `%s` instead of `%s`.',
                $result->getExpectedClassName(),
                $result->getActualClassName()
            ),
            $this->getClassNameDeclarationPosition($phpcsFile, $typePointer),
            self::CODE_INCORRECT_CLASS_NAME
        );
    }

    private function getClassNameDeclarationPosition(
        File $phpcsFile,
        int $typePointer
    ): ?int {
        return TokenHelper::findNext($phpcsFile, \T_STRING, $typePointer + 1);
    }

    private function getClassFileOf(
        File $phpcsFile,
        $typePointer
    ): ClassFileUnderInspection {
        return new ClassFileUnderInspection(
            $phpcsFile->getFilename(),
            ClassHelper::getFullyQualifiedName($phpcsFile, $typePointer)
        );
    }
}
