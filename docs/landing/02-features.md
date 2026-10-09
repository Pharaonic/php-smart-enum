---
view: components.packages.features
variant: compact
badge: Key Features
title: Everything your enums are missing
subtitle: One trait, added to the enums you already have.
items:
  - icon: badge-check
    title: isActive() for Every Case
    text: "Each case gets its own check, named from the case: `isActive()` for `Active`, `isInProgress()` for `IN_PROGRESS`, `isOnHold()` for `onHold`."
  - icon: lines
    title: Names, Values & Labels
    text: "`names()`, `values()` and `labels()` list your cases in the shape you need."
  - icon: menu
    title: Select Options
    text: "`options()` maps each backing value to its label, ready for a dropdown."
  - icon: search
    title: Lookup by Case Name
    text: "`fromName()`, `tryFromName()` and `hasName()` work with case names, the way `from()` works with values."
  - icon: check-circle
    title: Strict Comparisons
    text: "`eq()`, `is()`, `isNot()`, `in()` and `notIn()` match a case, a case name or a backing value, with no type juggling."
  - icon: switch
    title: Unit & Backed Enums
    text: The same trait works on unit, string-backed and int-backed enums, and custom labels flow into `labels()` and `options()`.
---
