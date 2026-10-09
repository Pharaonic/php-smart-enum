# Changelog

All notable changes to this project will be documented in this file.

## 8.1.1 - Unreleased

### Changed

- The package archive no longer includes `docs/`, so `composer require` installs only the library files.

## 8.1.0 - 2026-10-09

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

- Requires PHP `>=8.1 <8.2`.
