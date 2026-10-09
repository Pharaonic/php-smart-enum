# Contributing

Contributions are welcome. Please follow the workflow below before opening a pull request.

## Scope

This package adds a small set of helpers to **native** PHP enums through the `SmartEnum` trait, plus one `is<Case>()` check per case resolved by `__call()`. The following are out of scope:

- Replacing or wrapping native methods (`cases()`, `from()`, `tryFrom()`).
- Other magic: `__callStatic()`, `__get()`, or `__call()` behavior beyond the `is<Case>()` checks.
- Enum base classes, code generation, Composer plugins, or source rewriting.
- Framework integrations (Laravel, Symfony, …). Those belong in separate packages.
- Runtime dependencies.

Please open an issue before proposing a new public method.

## Supported PHP versions

PHP 8.1 (`>=8.1 <8.2`). Code must not use syntax or functions introduced after PHP 8.1.

## 1. Fork

Fork [Pharaonic/php-smart-enum](https://github.com/Pharaonic/php-smart-enum) on GitHub.

## 2. Clone

```bash
git clone https://github.com/YOUR_USERNAME/php-smart-enum.git
cd php-smart-enum
git remote add upstream https://github.com/Pharaonic/php-smart-enum.git
```

## 3. Install dependencies

```bash
composer install
```

## 4. Create a branch

```bash
git checkout -b fix/short-description
```

Recommended prefixes:

```text
feature/
fix/
refactor/
test/
docs/
```

## 5. Run tests

```bash
composer test      # PHPUnit
composer analyse   # PHPStan (level max)
composer lint      # PHP_CodeSniffer (PSR-12)
composer check     # all of the above
composer validate --strict
```

All checks must pass. CI runs them on PHP 8.1.

## 6. Follow the coding style

- PSR-12, enforced by `composer lint`; `composer format` fixes most issues.
- `declare(strict_types=1);` in every PHP file.
- Strict comparisons only (`===`). Loose comparison needs a documented reason.
- Do not assume every enum is backed: handle `UnitEnum` and `BackedEnum` deliberately.
- Keep the trait small. Do not add internal services or abstraction layers until a real need exists.

## 7. Open a pull request

```bash
git push origin fix/short-description
```

Open the pull request and fill in the template.

- Keep each change focused.
- Add or update tests for every behavior change.
- Add an entry to `CHANGELOG.md` under `Unreleased`.
- Update `docs/` (the pharaonic.dev documentation) when the public API or usage changes.

## Public API changes

The `SmartEnum` trait is mixed into users' own enums, so every public method name, parameter and return type is part of their code. Adding, renaming or changing a public method can break enums that declare a method with the same name, and changing a result can break callers. Public API changes need careful review and an issue discussion first; backward compatibility matters.

## Code of Conduct

This project follows the [Code of Conduct](CODE_OF_CONDUCT.md).

## Security

Do not report security vulnerabilities through public issues. See [SECURITY.md](SECURITY.md).
