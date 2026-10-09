:::badges
- PHP Package {color=blue}
- {release.label} {color=green}
- {package.license} License {color=purple}
:::

# Smart Enum

Smart, lightweight helpers for native PHP enums. Add the `SmartEnum` trait to any enum you already have and get helpers for case names, backing values, labels, select options, lookup by case name, comparisons and `is<Case>()` checks. Native methods (`cases()`, `from()`, `tryFrom()`) stay native, and the package has no base class, no generated code and no runtime dependencies.

:::features
### Names, Values & Labels {icon="lines"}
List the case names, the backing values, or the labels of every case.

### Select Options {icon="menu"}
Map each backing value to a human-readable label, ready for a dropdown.

### Lookup by Case Name {icon="search"}
Find a case by its name, the same way from() finds one by its value.

### Comparisons {icon="check-circle"}
Compare a case with another case, a case name or a backing value, always strictly.

### Case Checks {icon="badge-check"}
Ask a case about itself with isActive(), isPending() and one check per case.

### Unit & Backed Enums {icon="switch"}
The same trait works on unit, string-backed and int-backed enums.
:::

:::info Quick Tip
Keep using PHP's own `Status::cases()`, `Status::from()` and `Status::tryFrom()`. SmartEnum only adds what PHP doesn't have.
:::
