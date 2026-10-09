<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests;

use Pharaonic\SmartEnum\Tests\Fixtures\IntStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\MixedCaseStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\StringStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\UnitStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ValueError;

/**
 * Verifies the static SmartEnum helpers on unit and backed enums.
 */
final class SmartEnumStaticTest extends TestCase
{
    /**
     * @return iterable<string, array{class-string<UnitStatus|StringStatus|IntStatus>, list<int|string>}>
     */
    public static function valuesProvider(): iterable
    {
        yield 'unit' => [UnitStatus::class, ['Pending', 'Active', 'Disabled']];
        yield 'string-backed' => [StringStatus::class, ['pending', 'active', 'disabled']];
        yield 'int-backed' => [IntStatus::class, [0, 1, 2]];
    }

    /**
     * @param class-string<UnitStatus|StringStatus|IntStatus> $enum
     */
    #[DataProvider('valuesProvider')]
    public function testNames(string $enum): void
    {
        $this->assertSame(['Pending', 'Active', 'Disabled'], $enum::names());
    }

    public function testNamesKeepCaseNamesAsDeclared(): void
    {
        $this->assertSame(
            ['PENDING', 'IN_COOKING', 'InProgress', 'onHold', 'HTTPError', 'Level2'],
            MixedCaseStatus::names(),
        );
    }

    /**
     * @param class-string<UnitStatus|StringStatus|IntStatus> $enum
     */
    #[DataProvider('valuesProvider')]
    public function testLabelsListTheCaseLabels(string $enum): void
    {
        $this->assertSame(['Pending', 'Active', 'Disabled'], $enum::labels());
    }

    /**
     * @param class-string<UnitStatus|StringStatus|IntStatus> $enum
     * @param list<int|string> $values
     */
    #[DataProvider('valuesProvider')]
    public function testValuesFallBackToCaseNamesOnUnitEnums(string $enum, array $values): void
    {
        $this->assertSame($values, $enum::values());
    }

    /**
     * @param class-string<UnitStatus|StringStatus|IntStatus> $enum
     * @param list<int|string> $values
     */
    #[DataProvider('valuesProvider')]
    public function testOptionsAreLabelsKeyedByValue(string $enum, array $values): void
    {
        $this->assertSame(array_combine($values, ['Pending', 'Active', 'Disabled']), $enum::options());
    }

    /**
     * @param class-string<UnitStatus|StringStatus|IntStatus> $enum
     */
    #[DataProvider('valuesProvider')]
    public function testFromNameAndTryFromNameFindTheCase(string $enum): void
    {
        foreach ($enum::cases() as $case) {
            $this->assertSame($case, $enum::fromName($case->name));
            $this->assertSame($case, $enum::tryFromName($case->name));
        }
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function unknownNameProvider(): iterable
    {
        yield 'unknown name' => ['Archived'];
        yield 'different case' => ['active'];
        yield 'backing value' => ['pending'];
        yield 'empty string' => [''];
    }

    #[DataProvider('unknownNameProvider')]
    public function testTryFromNameReturnsNullForAnUnknownName(string $name): void
    {
        $this->assertNull(StringStatus::tryFromName($name));
        $this->assertFalse(StringStatus::hasName($name));
    }

    #[DataProvider('unknownNameProvider')]
    public function testFromNameThrowsForAnUnknownName(string $name): void
    {
        $this->expectException(ValueError::class);
        $this->expectExceptionMessage("No case with name {$name} in enum " . StringStatus::class . '.');

        StringStatus::fromName($name);
    }

    /**
     * @param class-string<UnitStatus|StringStatus|IntStatus> $enum
     */
    #[DataProvider('valuesProvider')]
    public function testHasName(string $enum): void
    {
        $this->assertTrue($enum::hasName('Active'));
        $this->assertFalse($enum::hasName('ACTIVE'));
        $this->assertFalse($enum::hasName('Archived'));
    }

    /**
     * @return iterable<string, array{class-string<UnitStatus|StringStatus|IntStatus>, int|string, bool}>
     */
    public static function hasValueProvider(): iterable
    {
        yield 'string value' => [StringStatus::class, 'active', true];
        yield 'string case name' => [StringStatus::class, 'Active', false];
        yield 'string unknown' => [StringStatus::class, 'archived', false];
        yield 'int value' => [IntStatus::class, 1, true];
        yield 'int value as string' => [IntStatus::class, '1', false];
        yield 'int unknown' => [IntStatus::class, 3, false];
        yield 'unit case name' => [UnitStatus::class, 'Active', true];
        yield 'unit lower case' => [UnitStatus::class, 'active', false];
        yield 'unit int' => [UnitStatus::class, 1, false];
    }

    /**
     * @param class-string<UnitStatus|StringStatus|IntStatus> $enum
     */
    #[DataProvider('hasValueProvider')]
    public function testHasValueIsStrict(string $enum, int|string $value, bool $expected): void
    {
        $this->assertSame($expected, $enum::hasValue($value));
    }
}
