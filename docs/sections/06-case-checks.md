## Case Checks

Every case gets an `is<Case>()` check, resolved by the trait's `__call()`. It returns `true` only on the case it is named after.

```php
$status = Status::Active;

$status->isActive();   // true
$status->isPending();  // false
$status->isDisabled(); // false
```

You don't declare the checks: adding a case adds its check.

### From case name to check

| Case | Check |
| --- | --- |
| `Active` | `isActive()` |
| `PENDING` | `isPending()` |
| `IN_COOKING` | `isInCooking()` |
| `IN_PROGRESS` | `isInProgress()` |
| `OUT_FOR_DELIVERY` | `isOutForDelivery()` |
| `InProgress` | `isInProgress()` |
| `In_Progress` | `isInProgress()` |
| `onHold` | `isOnHold()` |
| `HTTPError` | `isHTTPError()` |
| `Level2` | `isLevel2()` |

```php
$status = OrderStatus::IN_PROGRESS;

$status->isInProgress();     // true
$status->isOutForDelivery(); // false
```

### How check names are built

The check name is `is` followed by the case name, with each underscore-separated word capitalized:

- A word written entirely in upper case is title-cased: `PENDING` becomes `isPending`, `IN_COOKING` becomes `isInCooking`.
- Any other word keeps its letters and gets an upper-case first letter: `onHold` becomes `isOnHold`, `HTTPError` becomes `isHTTPError`, `Level2` becomes `isLevel2`.

### Names that are not checks

Check names are matched exactly, letter case included. Each call below throws `Error: Call to undefined method`:

| Call | Why it fails | Write instead |
| --- | --- | --- |
| `isArchived()` | No case is named `Archived` | Add the case, or use `is('Archived')` for input you don't control |
| `pending()` | Checks start with `is` | `isPending()` |
| `hasPending()` | Only the `is` prefix makes a check | `isPending()` |
| `IsPending()` | The prefix is lower-case `is` | `isPending()` |
| `isPENDING()` | The raw case name is not used | `isPending()` |
| `isIN_COOKING()` | Underscores are removed | `isInCooking()` |
| `isinprogress()` | Each word starts with a capital letter | `isInProgress()` |
| `isonHold()` | The first letter after `is` is a capital | `isOnHold()` |
| `isHttpError()` | Mixed-case words keep their letters | `isHTTPError()` |
| `secret()` | A `protected` or `private` enum method can't be called from outside | Make it `public` |

```php
OrderStatus::PENDING->isPENDING();
// Error: Call to undefined method App\Enums\OrderStatus::isPENDING()
```

### Arguments are ignored

A check takes no arguments. Anything you pass is ignored, so `$status->isActive('anything', 1)` is the same as `$status->isActive()`.

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
