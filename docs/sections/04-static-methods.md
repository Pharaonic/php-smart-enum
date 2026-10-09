## Static Methods

Static helpers are called on the enum itself. The examples use the `Status` enum from [Basic Usage](#basic-usage).

### names()

Returns the case names, in declaration order.

```php
Status::names(); // ['Pending', 'Active', 'Disabled']
```

### values()

Returns the backing values, in declaration order.

```php
Status::values(); // ['pending', 'active', 'disabled']
```

On a unit enum it returns the case names instead. See [Unit vs Backed Enums](#unit-and-backed-enums).

### labels()

Returns the case labels, in declaration order.

```php
Status::labels(); // ['Pending', 'Active', 'Disabled']
```

Use `options()` when you need each label keyed by its value.

Each label comes from the case's `label()` method, so [custom labels](#labels) show up here too.

### options()

Returns each case's label, keyed by backing value (`value => label`), for select inputs.

```php
Status::options(); // ['pending' => 'Pending', 'active' => 'Active', 'disabled' => 'Disabled']
```

On a unit enum the keys are the case names.

### fromName() and tryFromName()

Find a case by its case name, the way `from()` and `tryFrom()` find one by its backing value. The name must match exactly, including letter case. `fromName()` throws a `ValueError` when no case matches; `tryFromName()` returns `null`.

```php
Status::fromName('Active');     // Status::Active
Status::tryFromName('active');  // null (that's a backing value, not a name)
Status::fromName('Archived');   // ValueError: No case with name Archived in enum App\Enums\Status.
```

### hasName() and hasValue()

Check whether a case name, or a backing value, exists. Both comparisons are strict.

```php
Status::hasName('Active');  // true
Status::hasValue('active'); // true
Status::hasValue('Active'); // false

Priority::hasValue(1);      // true  (int-backed)
Priority::hasValue('1');    // false (no type juggling)
```

On a unit enum, `hasValue()` checks the case names.
