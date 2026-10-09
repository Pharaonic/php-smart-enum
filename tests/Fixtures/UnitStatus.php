<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests\Fixtures;

use Pharaonic\SmartEnum\SmartEnum;

enum UnitStatus
{
    use SmartEnum;

    case Pending;
    case Active;
    case Disabled;
}
