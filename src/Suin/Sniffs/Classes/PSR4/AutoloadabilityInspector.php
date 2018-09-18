<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

final class AutoloadabilityInspector
{
    /**
     * @var string
     */
    private $baseDirectory;

    /**
     * @var string
     */
    private $namespacePrefix;

    public function __construct(string $baseDirectory, string $namespacePrefix)
    {
        $this->baseDirectory = \rtrim($baseDirectory, '/') . '/';
        $this->namespacePrefix = \rtrim($namespacePrefix, '\\') . '\\';
    }

    public function inspect(
        ClassFileUnderInspection $classFile
    ): InspectionResult {
        return $this->classFileIsUnderBaseDirectory($classFile) ?
            $this->inspectAutoloadability($classFile) :
            new PSR4UnrelatedClass();
    }

    private function inspectAutoloadability(
        ClassFileUnderInspection $classFile
    ): InspectionResult {
        $expectedClassName = $this->guessExpectedClassName($classFile);
        $actualClassName = $classFile->getClassName();
        return $expectedClassName === $actualClassName ?
            new AutoloadableClass() :
            new NonAutoloadableClass($expectedClassName, $actualClassName);
    }

    private function guessExpectedClassName(
        ClassFileUnderInspection $classFile
    ): string {
        $relativeFileName = $this->guessRelativeFileName($classFile);
        $relativeClassName = $this->guessRelativeClassName($relativeFileName);
        return $this->guessFullyQualifiedClassName($relativeClassName);
    }

    private function guessRelativeFileName(
        ClassFileUnderInspection $classFile
    ): string {
        \assert($this->directoryEndsWithSlash());
        \assert($this->classFileIsUnderBaseDirectory($classFile));
        return \substr(
            $classFile->getFileName(),
            \strlen($this->baseDirectory)
        );
    }

    private function guessRelativeClassName(string $relativeFileName): string
    {
        $basename = \basename($relativeFileName);
        $filename = \pathinfo($relativeFileName, \PATHINFO_FILENAME);
        $dirname = $basename === $relativeFileName ?
            '' :
            \pathinfo($relativeFileName, \PATHINFO_DIRNAME) . '/';
        return \str_replace('/', '\\', $dirname) . $filename;
    }

    private function guessFullyQualifiedClassName(
        string $relativeClassName
    ): string {
        \assert($this->namespaceEndsWithBackslash());
        return $this->namespacePrefix . $relativeClassName;
    }

    private function classFileIsUnderBaseDirectory(
        ClassFileUnderInspection $classFile
    ): bool {
        return \strpos($classFile->getFileName(), $this->baseDirectory) === 0;
    }

    private function namespaceEndsWithBackslash(): bool
    {
        return \substr($this->namespacePrefix, -1) === '\\';
    }

    private function directoryEndsWithSlash(): bool
    {
        return \substr($this->baseDirectory, -1) === '/';
    }
}
