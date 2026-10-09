## Troubleshooting

### Error: Call to undefined method Status::isactive()

Check names are matched exactly, including letter case: write `isActive()`, not `isactive()` or `IsActive()`. For a case named `IN_COOKING`, the check is `isInCooking()`. See [How check names are built](#case-checks).

### BadMethodCallException: Call to ambiguous method

Two of your cases produce the same check name, such as `IN_PROGRESS` and `InProgress`. Rename one of them, or compare with `$task->is(Task::InProgress)`.

### PHPStan or my IDE reports isActive() as undefined

`is<Case>()` checks are resolved at runtime by `__call()`. Add `@method bool isActive()` tags to the enum's docblock, as shown in [IDE and static analysis support](#case-checks).

### is('1') returns false on an int-backed enum

Matching is strict. Pass the integer: `$priority->is(1)`. Convert request input first, for example with `Priority::tryFrom((int) $input)`.

### My smart_enum_label() function is ignored

The function must be global (no namespace), loaded before the label is read (use Composer `files` autoloading), and return a string. An enum that declares its own `label()` does not call it.

### My own label() method is used instead of the trait's

PHP gives a method declared in the enum priority over a method from a trait. This is expected: if your enum declares `label()`, `is()` or another method with the same name, your version wins. Keep the same signature so code that relies on the trait keeps working.

### Trait method collision with another trait

If another trait used by the same enum also declares, for example, `is()`, PHP throws a fatal error. Resolve it with `insteadof`:

```php title="app/Enums/Status.php"
enum Status: string
{
    use SmartEnum, OtherTrait {
        SmartEnum::is insteadof OtherTrait;
    }

    case Active = 'active';
}
```

### Call to undefined method Direction::from()

`from()` and `tryFrom()` are native PHP methods that only exist on backed enums. For a unit enum, look cases up by name with `fromName()` / `tryFromName()`.

### Composer refuses to install the package

The package requires PHP 8.1 (`>=8.1 <8.2`); 8.1 is the version that introduced native enums. Check `php -v` and the `config.platform.php` setting in your `composer.json`.
