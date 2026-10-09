## Examples

### 1. Branching on a status

Read the checks like plain English when the code depends on the current case.

```php title="src/Orders/OrderNotifier.php"
public function notify(Order $order): void
{
    if ($order->status->isOutForDelivery()) {
        $this->sms->send($order->phone, 'Your order is on its way.');
    }

    if ($order->status->isDelivered()) {
        $this->mailer->send(new OrderDelivered($order));
    }
}
```

### 2. A select input

Build `<option>` tags from `options()`, which maps each backing value to its label.

- ===Enum

  ```php title="app/Enums/Status.php"
  <?php

  namespace App\Enums;

  use Pharaonic\SmartEnum\SmartEnum;

  enum Status: string
  {
      use SmartEnum;

      case Pending = 'pending';
      case Active = 'active';
      case Disabled = 'disabled';
  }
  ```

- ===Template

  ```php title="templates/status-select.php"
  <select name="status">
      <?php foreach (\App\Enums\Status::options() as $value => $label): ?>
          <option value="<?= htmlspecialchars((string) $value) ?>"><?= htmlspecialchars($label) ?></option>
      <?php endforeach ?>
  </select>
  ```

### 3. Validating input

Check a submitted value before converting it with PHP's own `from()`.

```php title="src/Http/UpdateStatus.php"
if (! Status::hasValue($input['status'])) {
    throw new InvalidArgumentException('Unknown status.');
}

$status = Status::from($input['status']);
```

### 4. Guarding a state change

Allow an action only for some cases.

```php title="src/Accounts/Account.php"
public function suspend(): void
{
    if ($this->status->notIn([Status::Pending, Status::Active])) {
        throw new LogicException('Only pending or active accounts can be suspended.');
    }

    $this->status = Status::Disabled;
}
```

### 5. Configuration by case name

Read a case name from configuration or the environment and resolve it, with a fallback.

```php title="config/app.php"
$defaultStatus = Status::tryFromName(getenv('DEFAULT_STATUS') ?: '') ?? Status::Pending;
```

### 6. Serializing for an API

Return the case metadata from `info()` in a JSON response.

```php title="src/Http/StatusController.php"
echo json_encode(array_map(
    static fn (Status $status): array => $status->info(),
    Status::cases(),
));
```
