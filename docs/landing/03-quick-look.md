---
view: components.packages.quick-look
title: A quick look
subtitle: Add the trait and every case answers its own is…() check, whatever style its name is written in.
file: app/Enums/OrderStatus.php
language: php
code: |
  use Pharaonic\SmartEnum\SmartEnum;

  enum OrderStatus: string
  {
      use SmartEnum;

      case PENDING = 'pending';
      case IN_PROGRESS = 'in_progress';
      case OUT_FOR_DELIVERY = 'out_for_delivery';
      case Delivered = 'delivered';
  }

  $status = OrderStatus::IN_PROGRESS;

  $status->isInProgress();     // true
  $status->isOutForDelivery(); // false
  $status->in(['pending', 'in_progress']); // true
---
