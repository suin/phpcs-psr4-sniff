<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

final class AutoloadableClass implements InspectionResult
{
    public function isAutoloadable(): bool
    {
        return true;
    }

    public function isPsr4RelatedClass(): bool
    {
        return true;
    }
}
