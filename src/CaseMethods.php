<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum;

/**
 * Registry of the is<Case>() method names of each enum.
 *
 * The map of an enum is built on first use and kept for the rest of the
 * request, so later calls are a single array lookup.
 *
 * @internal
 */
final class CaseMethods
{
    /**
     * Cases keyed by enum class, then by method name.
     *
     * @var array<class-string<\UnitEnum>, array<string, list<\UnitEnum>>>
     */
    private static array $methods = [];

    private function __construct()
    {
    }

    /**
     * Cases of the given enum keyed by their is<Case>() method name.
     *
     * More than one case under a name means the name is ambiguous.
     *
     * @param class-string<\UnitEnum> $enum
     *
     * @return array<string, list<\UnitEnum>>
     */
    public static function of(string $enum): array
    {
        if (isset(self::$methods[$enum])) {
            return self::$methods[$enum];
        }

        $methods = [];

        foreach ($enum::cases() as $case) {
            $methods[self::methodName($case->name)][] = $case;
        }

        return self::$methods[$enum] = $methods;
    }

    /**
     * Cases of the given enum that answer to the given method name.
     *
     * @param class-string<\UnitEnum> $enum
     *
     * @return list<\UnitEnum>
     */
    public static function cases(string $enum, string $method): array
    {
        return self::of($enum)[$method] ?? [];
    }

    /**
     * The is<Case>() method name of the given case name.
     *
     * Each underscore-separated word is capitalized; a word that is entirely
     * upper case is title-cased (PENDING => Pending, IN_COOKING => InCooking),
     * any other word keeps its letters (onHold => OnHold, HTTPError => HTTPError).
     */
    public static function methodName(string $case): string
    {
        $method = 'is';

        foreach (explode('_', $case) as $word) {
            $method .= ucfirst(strtoupper($word) === $word ? strtolower($word) : $word);
        }

        return $method;
    }
}
