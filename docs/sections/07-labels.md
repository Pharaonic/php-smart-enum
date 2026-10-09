## Labels

`label()` returns the case name by default. `labels()`, `options()` and `info()` all read their labels from `label()`, so changing it once changes them all.

### Per enum: override label()

Declare `label()` in the enum. A method declared in the enum takes priority over the trait's.

```php title="app/Enums/Status.php"
enum Status: string
{
    use SmartEnum;

    case Pending = 'pending';
    case Active = 'active';
    case Disabled = 'disabled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Waiting for review',
            self::Active => 'Active',
            self::Disabled => 'Disabled',
        };
    }
}

Status::options(); // ['pending' => 'Waiting for review', 'active' => 'Active', 'disabled' => 'Disabled']
```

### App-wide: the smart_enum_label() hook

When a global function named `smart_enum_label()` exists, the trait's `label()` calls it with the case. If it returns a string, that string is the label. If it returns anything else, such as `null`, the case name is used.

```php title="src/helpers.php"
<?php

use App\Enums\Status;

function smart_enum_label(UnitEnum $case): ?string
{
    if ($case instanceof Status) {
        return ucwords(strtolower(str_replace('_', ' ', $case->name)));
    }

    return null; // other enums keep their case names
}
```

The function must be loaded before any label is read. Load it with Composer's `files` autoloading:

```json title="composer.json"
{
    "autoload": {
        "files": ["src/helpers.php"]
    }
}
```

:::info Translations
The hook is a good place to translate labels, for example by looking the case up in your translation files. An enum that overrides `label()` itself does not call the hook.
:::
