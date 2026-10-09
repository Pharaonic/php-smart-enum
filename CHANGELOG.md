# Changelog

All notable changes to this project will be documented in this file.

## 8.5.1 - 2026-10-09

### Changed

- Documentation puts the `is<Case>()` checks first, with a case-name-to-check table (`IN_PROGRESS` → `isInProgress()`), the names that are not checks and what to write instead, and a status-branching example.

## 8.5.0 - 2026-10-09

### Added

- `SmartEnum` trait for native enums.
- Static helpers: `names()`, `labels()`, `values()`, `options()`, `fromName()`, `tryFromName()`, `hasName()`, `hasValue()`.
- Instance helpers: `label()`, `eq()`, `is()`, `isNot()`, `in()`, `notIn()`, `info()`.
- `is<Case>()` checks for every case, resolved by `__call()`; a check shared by two cases throws `BadMethodCallException`.
- Optional global `smart_enum_label()` hook for labels.
- Unit enum support: `values()`, `options()`, `hasValue()`, `info()` and `is()` use the case name as its value.
- Tests, PHPStan (level max), PHP_CodeSniffer (PSR-12) and CI configuration.
- Repository documentation and contribution files.
- Versioned documentation in `docs/` for pharaonic.dev.

### Compatibility

- Requires PHP `>=8.5 <8.6`.
