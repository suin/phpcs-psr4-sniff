<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

final class NonAutoloadableClass implements InspectionResult
{
    /**
     * @var string
     */
    private $expectedClassName;

    /**
     * @var string
     */
    private $actualClassName;

    public function __construct(
        string $expectedClassName,
        string $actualClassName
    ) {
        $this->expectedClassName = $expectedClassName;
        $this->actualClassName = $actualClassName;
    }

    public function isAutoloadable(): bool
    {
        return false;
    }

    public function isPsr4RelatedClass(): bool
    {
        return true;
    }

    public function getExpectedClassName(): string
    {
        return $this->expectedClassName;
    }

    public function getActualClassName(): string
    {
        return $this->actualClassName;
    }
}
