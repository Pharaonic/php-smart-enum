---
view: components.packages.package-hero
badges:
  - label: PHP Package
    color: blue
  - label: "{package.latestVersionLabel}"
    color: green
  - label: "{package.license} License"
    color: purple
  - label: "{package.downloadsShort}+ downloads"
    color: blue
eyebrow: "{package.name}"
title: Native enums,
highlight: a little smarter
buttons:
  - label: View Full Documentation
    href: "{card.docsUrl}"
    style: primary
    icon: arrow-right
  - label: View on GitHub
    href: "{package.githubUrl}"
    style: ghost
    external: true
install: "{card.install}"
labels:
  copy: Copy
  copied: Copied!
---

Add `use SmartEnum;` to any native PHP enum and get helpers for names, values, labels, options, lookup by case name, strict comparisons and `isActive()`-style checks. Your enums stay native: no base class, no code generation, no framework.
