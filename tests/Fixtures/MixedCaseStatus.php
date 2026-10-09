<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests\Fixtures;

use Pharaonic\SmartEnum\SmartEnum;

/**
 * Case names in mixed naming styles, for name and label handling.
 */
enum MixedCaseStatus: string
{
    use SmartEnum;

    case PENDING = 'pending';
    case IN_COOKING = 'in_cooking';
    case InProgress = 'in_progress';
    case onHold = 'on_hold';
    case HTTPError = 'http_error';
    case Level2 = 'level_2';

    /**
     * Not callable from outside; SmartEnum::__call() must not expose it.
     */
    protected function tag(string $prefix, string $suffix = ''): string
    {
        return $prefix . $this->name . $suffix;
    }
}
