## Instance Methods

Instance helpers are called on a case. The examples use the `Status` enum from [Basic Usage](#basic-usage).

### label()

Returns the human-readable label of the case. By default it is the case name.

```php
Status::Active->label(); // 'Active'
```

See [Labels](#labels) to change it.

### eq()

Strict comparison with another enum case. It only accepts an enum case, and a case of another enum never matches, even with the same name.

```php
Status::Active->eq(Status::Active);   // true
Status::Active->eq(Status::Disabled); // false
```

### is() and isNot()

A comparison that accepts an enum case, a case name, or a backing value (`string` or `int`). `isNot()` is the inverse.

```php
$status = Status::Active;

$status->is(Status::Active);      // true
$status->is('Active');            // true (case name)
$status->is('active');            // true (backing value)
$status->is('ACTIVE');            // false
$status->isNot(Status::Disabled); // true
```

The matching rules:

- An enum case matches only when it is the same case, like `eq()`.
- A string or an int matches when it equals **this case's own** name or backing value.
- Comparison is strict: an int-backed case matches `1`, never `'1'`.

```php
$priority = Priority::High; // case High = 1

$priority->is(1);   // true
$priority->is('1'); // false
```

:::info A name that is another case's value
Because `is()` looks at the case's own name and value only, a string can match two cases when one case's name is another case's backing value. With `case Open = 'Closed'` and `case Closed = 'Open'`, both `Status::Open->is('Open')` and `Status::Closed->is('Open')` are `true`. Pass the case itself, or use `eq()`, when you need an exact match.
:::

### in() and notIn()

Check whether the case matches any of the given values. Each value is matched like the argument of `is()`, and the list may mix cases, names and values. `notIn()` is the inverse. An empty list never matches.

```php
$status->in([Status::Active, Status::Pending]); // true
$status->in(['active', 'pending']);             // true
$status->notIn([Status::Disabled]);             // true
$status->in([]);                                // false
```

### info()

Returns metadata about the case: its name, backing value and label.

```php
Status::Active->info(); // ['name' => 'Active', 'value' => 'active', 'label' => 'Active']
```

On a unit enum, `value` is the case name.
