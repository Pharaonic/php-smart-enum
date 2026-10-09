# Changelog

All notable changes to this project will be documented in this file.

## 8.3.0 - Unreleased

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

- Requires PHP `>=8.3 <8.4`.
