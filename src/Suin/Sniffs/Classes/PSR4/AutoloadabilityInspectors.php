<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

final class AutoloadabilityInspectors
{
    /**
     * @var AutoloadabilityInspector[]
     */
    private $inspectors;

    public function __construct(AutoloadabilityInspector ...$inspectors)
    {
        $this->inspectors = $inspectors;
    }

    /**
     * @noinspection MultipleReturnStatementsInspection
     * @param ClassFileUnderInspection $classFile
     */
    public function inspect(
        ClassFileUnderInspection $classFile
    ): InspectionResult {
        foreach ($this->inspectors as $inspector) {
            $result = $inspector->inspect($classFile);

            if ($result->isPsr4RelatedClass()) {
                return $result;
            }
        }
        return new PSR4UnrelatedClass();
    }
}
