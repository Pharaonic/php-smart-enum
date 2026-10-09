<?php

declare(strict_types=1);

use Pharaonic\SmartEnum\Tests\Fixtures\StringStatus;

/**
 * Global label hook picked up by SmartEnum::label().
 *
 * Labels StringStatus cases only; returns null for any other enum so the trait falls back to the case name.
 */
function smart_enum_label(\UnitEnum $case): ?string
{
    return $case instanceof StringStatus ? strtoupper($case->name) : null;
}
