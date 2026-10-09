<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum\Tests;

use Pharaonic\SmartEnum\SmartEnum;
use Pharaonic\SmartEnum\Tests\Fixtures\IntStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\MixedCaseStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\StringStatus;
use Pharaonic\SmartEnum\Tests\Fixtures\UnitStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionEnum;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;

/**
 * Verifies the declared SmartEnum API (names, signatures, static-ness) without calling it.
 */
final class SmartEnumStructureTest extends TestCase
{
    /**
     * Public API: method => [static, [parameter name => type], return type].
     *
     * @var array<string, array{bool, array<string, string>, string}>
     */
    private const API = [
        'names' => [true, [], 'array'],
        'labels' => [true, [], 'array'],
        'values' => [true, [], 'array'],
        'options' => [true, [], 'array'],
        'fromName' => [true, ['name' => 'string'], 'static'],
        'tryFromName' => [true, ['name' => 'string'], '?static'],
        'hasName' => [true, ['name' => 'string'], 'bool'],
        'hasValue' => [true, ['value' => 'int|string'], 'bool'],
        'label' => [false, [], 'string'],
        'eq' => [false, ['enum' => 'UnitEnum'], 'bool'],
        '__call' => [false, ['name' => 'string', 'arguments' => 'array'], 'mixed'],
        'is' => [false, ['value' => 'UnitEnum|int|string'], 'bool'],
        'isNot' => [false, ['value' => 'UnitEnum|int|string'], 'bool'],
        'in' => [false, ['values' => 'array'], 'bool'],
        'notIn' => [false, ['values' => 'array'], 'bool'],
        'info' => [false, [], 'array'],
    ];

    /**
     * @return iterable<string, array{string, bool, array<string, string>, string}>
     */
    public static function apiProvider(): iterable
    {
        foreach (self::API as $method => [$static, $parameters, $return]) {
            yield $method => [$method, $static, $parameters, $return];
        }
    }

    /**
     * @return iterable<string, array{class-string<\UnitEnum>}>
     */
    public static function fixtureProvider(): iterable
    {
        yield 'unit' => [UnitStatus::class];
        yield 'string-backed' => [StringStatus::class];
        yield 'int-backed' => [IntStatus::class];
        yield 'mixed-case' => [MixedCaseStatus::class];
    }

    public function testSmartEnumIsATrait(): void
    {
        $this->assertTrue(trait_exists(SmartEnum::class));
        $this->assertTrue((new ReflectionClass(SmartEnum::class))->isTrait());
    }

    public function testTraitDeclaresExactlyTheAgreedApi(): void
    {
        $declared = array_map(
            static fn (ReflectionMethod $method): string => $method->getName(),
            (new ReflectionClass(SmartEnum::class))->getMethods(),
        );

        sort($declared);
        $expected = array_keys(self::API);
        sort($expected);

        $this->assertSame($expected, $declared);
    }

    public function testTraitHasNoStateOrNestedTraits(): void
    {
        $trait = new ReflectionClass(SmartEnum::class);

        $this->assertSame([], $trait->getProperties());
        $this->assertSame([], $trait->getReflectionConstants());
        $this->assertSame([], $trait->getTraitNames());
    }

    /**
     * @param array<string, string> $parameters
     */
    #[DataProvider('apiProvider')]
    public function testMethodSignature(string $method, bool $static, array $parameters, string $return): void
    {
        $reflection = new ReflectionMethod(SmartEnum::class, $method);

        $this->assertTrue($reflection->isPublic(), "{$method}() must be public.");
        $this->assertSame($static, $reflection->isStatic(), "{$method}() static-ness.");
        $this->assertFalse($reflection->isAbstract(), "{$method}() must not be abstract.");
        $this->assertSame(count($parameters), $reflection->getNumberOfParameters(), "{$method}() parameter count.");
        $this->assertSame(
            count($parameters),
            $reflection->getNumberOfRequiredParameters(),
            "{$method}() required parameters.",
        );

        foreach ($reflection->getParameters() as $index => $parameter) {
            $name = array_keys($parameters)[$index];

            $this->assertSame($name, $parameter->getName(), "{$method}() parameter #{$index} name.");
            $this->assertFalse($parameter->isVariadic(), "{$method}() \${$name} must not be variadic.");
            $this->assertFalse($parameter->isPassedByReference(), "{$method}() \${$name} must not be by reference.");
            $this->assertSame($parameters[$name], self::describe($parameter->getType()), "{$method}() \${$name} type.");
        }

        $this->assertSame($return, self::describe($reflection->getReturnType()), "{$method}() return type.");
    }

    public function testTraitUsesNoMagicMethodsBesidesCall(): void
    {
        $trait = new ReflectionClass(SmartEnum::class);

        foreach (['__callStatic', '__get', '__set', '__isset', '__invoke'] as $magic) {
            $this->assertFalse($trait->hasMethod($magic), "SmartEnum must not declare {$magic}().");
        }
    }

    public function testTraitDoesNotRedefineNativeEnumMethods(): void
    {
        $trait = new ReflectionClass(SmartEnum::class);

        foreach (['cases', 'from', 'tryFrom'] as $native) {
            $this->assertFalse($trait->hasMethod($native), "SmartEnum must not declare {$native}().");
        }
    }

    /**
     * @param class-string<\UnitEnum> $enum
     */
    #[DataProvider('fixtureProvider')]
    public function testFixtureUsesTraitAndExposesApi(string $enum): void
    {
        $reflection = new ReflectionEnum($enum);

        $this->assertContains(SmartEnum::class, $reflection->getTraitNames());

        foreach (self::API as $method => [$static]) {
            $this->assertTrue($reflection->hasMethod($method), "{$enum} is missing {$method}().");
            $this->assertSame(
                $static,
                $reflection->getMethod($method)->isStatic(),
                "{$enum}::{$method}() static-ness.",
            );
        }
    }

    public function testFixturesCoverUnitAndBackedEnums(): void
    {
        /** @var array<class-string<\UnitEnum>, ?string> $backing */
        $backing = [
            UnitStatus::class => null,
            StringStatus::class => 'string',
            IntStatus::class => 'int',
        ];

        foreach ($backing as $enum => $type) {
            $reflection = new ReflectionEnum($enum);

            $this->assertSame($type !== null, $reflection->isBacked(), "{$enum} backed-ness.");
            $this->assertSame((string) $type, (string) $reflection->getBackingType(), "{$enum} backing type.");
            $this->assertSame(
                ['Pending', 'Active', 'Disabled'],
                array_map(static fn (\UnitEnum $case): string => $case->name, $enum::cases()),
            );
        }
    }

    /**
     * Normalises a type to a string.
     *
     * Union members are sorted (classes first) so declaration order does not matter.
     */
    private static function describe(?ReflectionType $type): string
    {
        if ($type instanceof ReflectionUnionType) {
            $names = array_map(
                static fn (ReflectionType $member): string => self::describe($member),
                $type->getTypes(),
            );
            usort(
                $names,
                static fn (string $a, string $b): int => [ctype_lower($a[0]), $a] <=> [ctype_lower($b[0]), $b],
            );

            return implode('|', $names);
        }

        if ($type instanceof ReflectionNamedType) {
            return ($type->allowsNull() && $type->getName() !== 'mixed' ? '?' : '') . $type->getName();
        }

        return 'none';
    }
}
