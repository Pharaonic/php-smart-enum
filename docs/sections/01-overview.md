:::badges
- PHP Package {color=blue}
- {release.label} {color=green}
- {package.license} License {color=purple}
:::

# Smart Enum

Smart, lightweight helpers for native PHP enums. Add the `SmartEnum` trait to any enum you already have and every case can answer for itself: `$status->isActive()` for `Active`, `$order->isInProgress()` for `IN_PROGRESS`. You also get helpers for case names, backing values, labels, select options, lookup by case name and comparisons. Native methods (`cases()`, `from()`, `tryFrom()`) stay native, and the package has no base class, no generated code and no runtime dependencies.

:::features
### isActive() for Every Case {icon="badge-check"}
Each case gets its own check, named from the case: IN_PROGRESS answers isInProgress().

### Names, Values & Labels {icon="lines"}
List the case names, the backing values, or the labels of every case.

### Select Options {icon="menu"}
Map each backing value to a human-readable label, ready for a dropdown.

### Lookup by Case Name {icon="search"}
Find a case by its name, the same way from() finds one by its value.

### Comparisons {icon="check-circle"}
Compare a case with another case, a case name or a backing value, always strictly.

### Unit & Backed Enums {icon="switch"}
The same trait works on unit, string-backed and int-backed enums.
:::

:::info Quick Tip
Write case names in the style you like. `IN_PROGRESS`, `InProgress` and `In_Progress` all get the same check, `isInProgress()`, and PHP's own `cases()`, `from()` and `tryFrom()` keep working as before.
:::
