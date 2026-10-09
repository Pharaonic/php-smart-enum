<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests;

use Pharaonic\SmartEnum\Tests\Fixtures\IntStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\StringStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\UnitStatus;
use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;

/**
 * Verifies the global smart_enum_label() hook.
 *
 * Each test runs in its own process: a global function cannot be undefined, and it must not leak into other tests.
 */
#[RunTestsInSeparateProcesses]
#[PreserveGlobalState(false)]
final class SmartEnumLabelHookTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/Fixtures/label-hook.php';
    }

    public function testLabelUsesTheHook(): void
    {
        $this->assertSame('ACTIVE', StringStatus::Active->label());
        $this->assertSame(['PENDING', 'ACTIVE', 'DISABLED'], StringStatus::labels());
        $this->assertSame(
            ['pending' => 'PENDING', 'active' => 'ACTIVE', 'disabled' => 'DISABLED'],
            StringStatus::options(),
        );
        $this->assertSame('ACTIVE', StringStatus::Active->info()['label']);
    }

    public function testLabelFallsBackToTheCaseNameWhenTheHookReturnsNoString(): void
    {
        $this->assertSame('Active', UnitStatus::Active->label());
        $this->assertSame('Active', IntStatus::Active->label());
    }
}
