<?php

declare(strict_types=1);

namespace Suin\Sniffs\Classes\PSR4;

interface InspectionResult
{
    public function isAutoloadable(): bool;

    public function isPsr4RelatedClass(): bool;
}
