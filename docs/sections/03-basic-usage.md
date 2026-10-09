## Basic Usage

### Add the trait

Import the trait and use it inside a native enum. Backed and unit enums work the same way.

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

### Native methods stay native

PHP itself provides these methods. SmartEnum does not redefine or wrap them:

```php
Status::cases();            // [Status::Pending, Status::Active, Status::Disabled]
Status::from('active');     // Status::Active
Status::tryFrom('missing'); // null
```

`from()` and `tryFrom()` exist on backed enums only.

### SmartEnum helpers

The trait adds static helpers on the enum and instance helpers on each case:

```php
Status::names();            // ['Pending', 'Active', 'Disabled']
Status::options();          // ['pending' => 'Pending', 'active' => 'Active', 'disabled' => 'Disabled']
Status::fromName('Active'); // Status::Active

$status = Status::Active;

$status->label();                              // 'Active'
$status->is('active');                         // true
$status->in([Status::Active, Status::Pending]); // true
$status->isActive();                           // true
```

See [Static Methods](#static-methods), [Instance Methods](#instance-methods) and [Case Checks](#case-checks) for each one.
