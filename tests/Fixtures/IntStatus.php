<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests\Fixtures;

use Pharaonic\SmartEnum\SmartEnum;

enum IntStatus: int
{
    use SmartEnum;

    case Pending = 0;
    case Active = 1;
    case Disabled = 2;
}
