---
view: components.home.faq
badge: FAQ
title: "{package.name}"
highlight: Questions
subtitle: "Quick answers about installing and using {package.name}."
---

## What is {package.name}?

{card.description} It's a free, open-source {technology.name} package by Pharaonic.

## How do I install {package.name}?

Run `composer require {package.composer}` in your project's root directory.

## What does {package.name} require?

The latest release requires {package.requiresText}.

## Does it replace cases(), from() or tryFrom()?

No. Those methods belong to PHP and stay untouched. {package.name} only adds new helpers next to them.

## Does it work with unit (non-backed) enums?

Yes. A unit enum has no backing values, so `values()`, `options()`, `hasValue()` and `info()` use the case names instead.

## How do I customize labels?

Declare a `label()` method in the enum, or define a global `smart_enum_label()` function for every enum at once. `labels()`, `options()` and `info()` pick the change up.

## Will my IDE know about $status->isActive()?

The `is<Case>()` checks are resolved at runtime, so add `@method bool isActive()` tags to the enum's docblock for IDEs and PHPStan.

## Does it work with Laravel?

Yes. It's plain PHP, so it works in any framework, and it has no Laravel-specific code.

## Is {package.name} free to use?

Yes. {package.name} is open source under the {package.license} license, so you can use it in personal and commercial projects.

## Where can I find the {package.name} documentation?

Read the [{package.name} documentation]({package.docsUrl}) for setup, usage, and the full API reference.

## How do I report a bug or contribute to {package.name}?

Open an issue or a pull request on [GitHub]({package.githubUrl}), or ask in the [Pharaonic Discord](https://discord.gg/XQG9RhvEvf).
