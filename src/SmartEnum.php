<?php

declare(strict_types=1);

namespace Pharaonic\SmartEnum;

/**
 * Convenience helpers for native PHP enums.
 *
 * Native methods (cases(), from(), tryFrom()) are provided by PHP itself
 * and are intentionally not redefined here.
 */
trait SmartEnum
{
    /**
     * Resolves is<Case>() checks.
     *
     * @param array<int|string, mixed> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        $matches = CaseMethods::cases(static::class, $name);

        if (count($matches) === 1) {
            return $this === $matches[0];
        }

        if (count($matches) > 1) {
            throw new \BadMethodCallException(sprintf(
                'Call to ambiguous method %s::%s(): matches cases %s.',
                static::class,
                $name,
                implode(', ', array_map(static fn (\UnitEnum $match): string => $match->name, $matches)),
            ));
        }

        throw new \Error(sprintf('Call to undefined method %s::%s()', static::class, $name));
    }

    /**
     * Case names.
     *
     * @return list<string>
     */
    public static function names(): array
    {
        return array_map(static fn (\UnitEnum $case): string => $case->name, static::cases());
    }

    /**
     * Case labels.
     *
     * @return list<string>
     */
    public static function labels(): array
    {
        return array_map(static fn (self $case): string => $case->label(), static::cases());
    }

    /**
     * Backing values; case names for a unit enum.
     *
     * @return list<int|string>
     */
    public static function values(): array
    {
        $values = [];

        foreach (static::cases() as $case) {
            $values[] = $case instanceof \BackedEnum ? $case->value : $case->name;
        }

        return $values;
    }

    /**
     * Case labels, keyed by backing value; by case name for a unit enum.
     *
     * @return array<int|string, string>
     */
    public static function options(): array
    {
        return array_combine(static::values(), static::labels());
    }

    /**
     * The case with the given case name.
     */
    public static function fromName(string $name): static
    {
        return static::tryFromName($name) ?? throw new \ValueError(sprintf(
            'No case with name %s in enum %s.',
            $name,
            static::class,
        ));
    }

    /**
     * The case with the given case name, or null when none matches.
     */
    public static function tryFromName(string $name): ?static
    {
        foreach (static::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }

    /**
     * Whether a case with the given case name exists.
     */
    public static function hasName(string $name): bool
    {
        return static::tryFromName($name) !== null;
    }

    /**
     * Whether a case with the given backing value exists; a case name for a unit enum.
     */
    public static function hasValue(string|int $value): bool
    {
        return in_array($value, static::values(), true);
    }

    /**
     * Human-readable label of this case.
     */
    public function label(): string
    {
        if (function_exists('smart_enum_label')) {
            $label = smart_enum_label($this);

            if (is_string($label)) {
                return $label;
            }
        }

        return $this->name;
    }

    /**
     * Whether the given enum case is this case.
     */
    public function eq(\UnitEnum $enum): bool
    {
        return $this === $enum;
    }

    /**
     * Whether the given enum case, case name or backing value matches this case.
     */
    public function is(\UnitEnum|string|int $value): bool
    {
        if ($value instanceof \UnitEnum) {
            return $this === $value;
        }

        return $this->name === $value || ($this instanceof \BackedEnum && $this->value === $value);
    }

    /**
     * Inverse of is().
     */
    public function isNot(\UnitEnum|string|int $value): bool
    {
        return !$this->is($value);
    }

    /**
     * Whether any of the given values matches this case.
     *
     * @param array<\UnitEnum|string|int> $values
     */
    public function in(array $values): bool
    {
        foreach ($values as $value) {
            if ($this->is($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Inverse of in().
     *
     * @param array<\UnitEnum|string|int> $values
     */
    public function notIn(array $values): bool
    {
        return !$this->in($values);
    }

    /**
     * Metadata about this case.
     *
     * @return array{name: string, value: int|string, label: string}
     */
    public function info(): array
    {
        return [
            'name' => $this->name,
            'value' => array_combine(static::names(), static::values())[$this->name],
            'label' => $this->label(),
        ];
    }
}
