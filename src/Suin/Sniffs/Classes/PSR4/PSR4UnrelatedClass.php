<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

final class PSR4UnrelatedClass implements InspectionResult
{
    public function isAutoloadable(): bool
    {
        return false;
    }

    public function isPsr4RelatedClass(): bool
    {
        return false;
    }
}
