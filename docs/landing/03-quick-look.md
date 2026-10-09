---
view: components.packages.quick-look
title: A quick look
subtitle: Add the trait to a native enum and use its helpers next to cases(), from() and tryFrom().
file: app/Enums/Status.php
language: php
code: |
  use Pharaonic\SmartEnum\SmartEnum;

  enum Status: string
  {
      use SmartEnum;

      case Pending = 'pending';
      case Active = 'active';
      case Disabled = 'disabled';
  }

  Status::options();  // ['pending' => 'Pending', 'active' => 'Active', ...]
  Status::fromName('Active')->isActive(); // true
  Status::Active->in(['pending', 'active']); // true
---
