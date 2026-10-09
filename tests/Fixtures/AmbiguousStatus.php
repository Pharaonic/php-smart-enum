<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests\Fixtures;

use Pharaonic\SmartEnum\SmartEnum;

/**
 * Two case names that resolve to the same is<Case>() method.
 */
enum AmbiguousStatus: string
{
    use SmartEnum;

    case IN_PROGRESS = 'in_progress';
    case InProgress = 'in-progress';
    case Done = 'done';
}
