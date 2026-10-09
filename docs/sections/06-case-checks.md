## Case Checks

Every case gets an `is<Case>()` check, resolved by the trait's `__call()`. It returns `true` only on the case it is named after.

```php
$status = Status::Active;

$status->isActive();   // true
$status->isPending();  // false
$status->isDisabled(); // false
```

Any arguments you pass to a check are ignored.

### How check names are built

The check name is `is` followed by the case name, with each underscore-separated word capitalized:

- A word written entirely in upper case is title-cased: `PENDING` becomes `isPending`, `IN_COOKING` becomes `isInCooking`.
- Any other word keeps its letters and gets an upper-case first letter: `onHold` becomes `isOnHold`, `HTTPError` becomes `isHTTPError`, `Level2` becomes `isLevel2`.

Check names are matched exactly. `isPENDING()`, `isonHold()`, `isHttpError()` and `IsPending()` are not checks, and calling them throws `Error: Call to undefined method`.

### Ambiguous checks

When two cases produce the same check name, for example `IN_PROGRESS` and `InProgress` (both `isInProgress`), calling that check throws a `BadMethodCallException` that names both cases. The checks of the other cases keep working.

```php
Task::Done->isInProgress();
// BadMethodCallException: Call to ambiguous method App\Enums\Task::isInProgress(): matches cases IN_PROGRESS, InProgress.
```

Use `is()` with the case itself when the names collide: `$task->is(Task::InProgress)`.

### Your own methods win

A method you declare in the enum is always called directly, never through `__call()`. If you declare `isActive()` yourself, your version is used.

A call that matches no check throws `Error: Call to undefined method`, the same error PHP throws without the trait. That includes your enum's own `protected` and `private` methods: calling one from outside the enum fails, as visibility says it should.

### IDE and static analysis support

IDEs and PHPStan can't see `__call()` checks on their own. Declare them with `@method` tags on the enum:

```php title="app/Enums/Status.php"
/**
 * @method bool isPending()
 * @method bool isActive()
 * @method bool isDisabled()
 */
enum Status: string
{
    use SmartEnum;

    case Pending = 'pending';
    case Active = 'active';
    case Disabled = 'disabled';
}
```
