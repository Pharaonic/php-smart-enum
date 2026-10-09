## Unit vs Backed Enums

PHP has two kinds of enums, and SmartEnum supports both:

```php title="app/Enums/Priority.php"
enum Priority: int   // backed: every case has a value
{
    use SmartEnum;

    case Low = 0;
    case High = 1;
}
```

```php title="app/Enums/Direction.php"
enum Direction       // unit: cases have a name only
{
    use SmartEnum;

    case Up;
    case Down;
}
```

Helpers that only need the case name work the same on both: `names()`, `labels()`, `fromName()`, `tryFromName()`, `hasName()`, `label()`, `eq()` and the `is<Case>()` checks.

A unit enum has no backing values, so the helpers that use them fall back to the case name:

| Helper | Backed enum | Unit enum |
| --- | --- | --- |
| `values()` | Backing values | Case names |
| `options()` | `value => label` | `name => label` |
| `hasValue($value)` | Checks the backing values | Checks the case names |
| `info()['value']` | The backing value | The case name |
| `is()` / `in()` | Case, name or value | Case or name |

```php
Direction::values();         // ['Up', 'Down']
Direction::options();        // ['Up' => 'Up', 'Down' => 'Down']
Direction::hasValue('Up');   // true
Direction::Up->info();       // ['name' => 'Up', 'value' => 'Up', 'label' => 'Up']
```

:::info Native methods on unit enums
PHP does not define `from()` or `tryFrom()` on unit enums. Use `fromName()` / `tryFromName()` to look up a unit case by name.
:::
