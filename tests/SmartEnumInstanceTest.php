<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests;

use Pharaonic\SmartEnum\Tests\Fixtures\CrossedStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\IntStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\MixedCaseStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\StringStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\UnitStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Verifies the instance SmartEnum helpers on unit and backed enums.
 */
final class SmartEnumInstanceTest extends TestCase
{
    public function testLabelDefaultsToTheCaseName(): void
    {
        foreach ([UnitStatus::class, StringStatus::class, IntStatus::class, MixedCaseStatus::class] as $enum) {
            foreach ($enum::cases() as $case) {
                $this->assertSame($case->name, $case->label());
            }
        }
    }

    public function testEqIsStrictCaseIdentity(): void
    {
        $this->assertTrue(StringStatus::Active->eq(StringStatus::Active));
        $this->assertFalse(StringStatus::Active->eq(StringStatus::Disabled));
        $this->assertFalse(StringStatus::Active->eq(UnitStatus::Active), 'Same name in another enum.');
        $this->assertFalse(IntStatus::Active->eq(UnitStatus::Active), 'Same name in another enum.');
    }

    /**
     * @return iterable<string, array{\UnitEnum, \UnitEnum|int|string, bool}>
     */
    public static function isProvider(): iterable
    {
        yield 'same case' => [StringStatus::Active, StringStatus::Active, true];
        yield 'other case' => [StringStatus::Active, StringStatus::Disabled, false];
        yield 'same name in another enum' => [StringStatus::Active, UnitStatus::Active, false];
        yield 'case name' => [StringStatus::Active, 'Active', true];
        yield 'backing value' => [StringStatus::Active, 'active', true];
        yield 'other case name' => [StringStatus::Active, 'Disabled', false];
        yield 'other backing value' => [StringStatus::Active, 'disabled', false];
        yield 'upper-case value' => [StringStatus::Active, 'ACTIVE', false];
        yield 'int value' => [IntStatus::Active, 1, true];
        yield 'int value as string' => [IntStatus::Active, '1', false];
        yield 'int case name' => [IntStatus::Active, 'Active', true];
        yield 'int other value' => [IntStatus::Active, 2, false];
        yield 'zero is not a name' => [IntStatus::Pending, 'Pending', true];
        yield 'zero is not an empty string' => [IntStatus::Pending, '', false];
        yield 'unit case name' => [UnitStatus::Active, 'Active', true];
        yield 'unit lower case' => [UnitStatus::Active, 'active', false];
        yield 'unit int' => [UnitStatus::Active, 1, false];
        yield 'own name over other value' => [CrossedStatus::Open, 'Open', true];
        yield 'own value over other name' => [CrossedStatus::Open, 'Closed', true];
        yield 'other own name' => [CrossedStatus::Closed, 'Closed', true];
        yield 'other own value' => [CrossedStatus::Closed, 'Open', true];
    }

    #[DataProvider('isProvider')]
    public function testIsAndIsNot(\UnitEnum $case, \UnitEnum|int|string $value, bool $expected): void
    {
        /** @var UnitStatus|StringStatus|IntStatus|CrossedStatus $case */
        $this->assertSame($expected, $case->is($value), 'is()');
        $this->assertSame(!$expected, $case->isNot($value), 'isNot()');
    }

    /**
     * @return iterable<string, array{list<\UnitEnum|int|string>, bool}>
     */
    public static function inProvider(): iterable
    {
        yield 'empty list' => [[], false];
        yield 'matching case' => [[StringStatus::Pending, StringStatus::Active], true];
        yield 'no matching case' => [[StringStatus::Pending, StringStatus::Disabled], false];
        yield 'matching value' => [['pending', 'active'], true];
        yield 'matching name' => [['Pending', 'Active'], true];
        yield 'mixed kinds' => [[StringStatus::Pending, 'Disabled', 'active'], true];
        yield 'nothing matches' => [[StringStatus::Pending, 'Disabled', 'archived', 1], false];
        yield 'same name in another enum' => [[UnitStatus::Active], false];
    }

    /**
     * @param list<\UnitEnum|int|string> $values
     */
    #[DataProvider('inProvider')]
    public function testInAndNotIn(array $values, bool $expected): void
    {
        $this->assertSame($expected, StringStatus::Active->in($values), 'in()');
        $this->assertSame(!$expected, StringStatus::Active->notIn($values), 'notIn()');
    }

    public function testInAcceptsAnAssociativeArray(): void
    {
        $this->assertTrue(StringStatus::Active->in(['first' => 'pending', 'second' => 'active']));
    }

    /**
     * @return iterable<string, array{\UnitEnum, array{name: string, value: int|string, label: string}}>
     */
    public static function infoProvider(): iterable
    {
        yield 'string-backed' => [StringStatus::Active, ['name' => 'Active', 'value' => 'active', 'label' => 'Active']];
        yield 'int-backed' => [IntStatus::Pending, ['name' => 'Pending', 'value' => 0, 'label' => 'Pending']];
        yield 'unit' => [UnitStatus::Active, ['name' => 'Active', 'value' => 'Active', 'label' => 'Active']];
    }

    /**
     * @param array{name: string, value: int|string, label: string} $expected
     */
    #[DataProvider('infoProvider')]
    public function testInfo(\UnitEnum $case, array $expected): void
    {
        /** @var UnitStatus|StringStatus|IntStatus $case */
        $this->assertSame($expected, $case->info());
    }
}
