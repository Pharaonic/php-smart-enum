<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests\Fixtures;

use Pharaonic\SmartEnum\SmartEnum;

/**
 * Each case name is the backing value of the other case, for is() matching rules.
 */
enum CrossedStatus: string
{
    use SmartEnum;

    case Open = 'Closed';
    case Closed = 'Open';
}
