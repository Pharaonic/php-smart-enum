## API Reference

All methods are declared on the `Pharaonic\SmartEnum\SmartEnum` trait.

### Static methods

| Method | Description | Returns |
| --- | --- | --- |
| `names()` | Case names | `array` (list of `string`) |
| `labels()` | Case labels | `array` (list of `string`) |
| `values()` | Backing values; case names on a unit enum | `array` (list of `int\|string`) |
| `options()` | Labels keyed by backing value; by case name on a unit enum | `array` (`int\|string => string`) |
| `fromName(string $name)` | The case with this case name, or a `ValueError` | `static` |
| `tryFromName(string $name)` | The case with this case name, or `null` | `?static` |
| `hasName(string $name)` | Whether a case with this name exists | `bool` |
| `hasValue(string\|int $value)` | Whether a case with this backing value exists; this name on a unit enum | `bool` |

### Instance methods

| Method | Description | Returns |
| --- | --- | --- |
| `label()` | Human-readable label of the case | `string` |
| `eq(UnitEnum $enum)` | Whether the given case is this case | `bool` |
| `is(UnitEnum\|string\|int $value)` | Matches this case, its name or its backing value | `bool` |
| `isNot(UnitEnum\|string\|int $value)` | Inverse of `is()` | `bool` |
| `in(array $values)` | Matches any of the given values | `bool` |
| `notIn(array $values)` | Inverse of `in()` | `bool` |
| `info()` | `['name' => …, 'value' => …, 'label' => …]`; `value` is the case name on a unit enum | `array` |
| `is<Case>()` | Whether this is the named case, through `__call()` | `bool` |

### Exceptions

| Thrown by | Exception | When |
| --- | --- | --- |
| `fromName()` | `ValueError` | No case has the given name |
| `is<Case>()` | `BadMethodCallException` | Two cases produce the same check name |
| `__call()` | `Error` | The name is not a check, or is a non-public method called from outside the enum |

### Label hook

| Function | Description | Returns |
| --- | --- | --- |
| `smart_enum_label(UnitEnum $case)` | Optional global function you define; used by `label()` when it returns a string | `?string` |

### Native methods (provided by PHP)

| Method | Description | Available on |
| --- | --- | --- |
| `cases()` | All cases, in declaration order | Unit and backed enums |
| `from(int\|string $value)` | The case with this backing value, or a `ValueError` | Backed enums |
| `tryFrom(int\|string $value)` | The case with this backing value, or `null` | Backed enums |
