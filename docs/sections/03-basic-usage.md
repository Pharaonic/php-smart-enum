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

### Ask a case what it is

Every case answers an `is<Case>()` check named after it. The name is built from the case name, so it reads naturally whatever style your cases use:

```php title="app/Enums/OrderStatus.php"
enum OrderStatus: string
{
    use SmartEnum;

    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case OUT_FOR_DELIVERY = 'out_for_delivery';
    case Delivered = 'delivered';
}
```

```php
$status = OrderStatus::IN_PROGRESS;

$status->isPending();        // false
$status->isInProgress();     // true
$status->isOutForDelivery(); // false
$status->isDelivered();      // false
```

See [Static Methods](#static-methods), [Instance Methods](#instance-methods) and [Case Checks](#case-checks) for each one.
