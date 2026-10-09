---
name: Smart Enum

action:
  label: View on Packagist
  href: "{package.packagistUrl}"

views: components.packages

breadcrumbs:
  - label: Home
    href: route:home
  - label: Packages
    href: route:packages.index
  - label: "{technology.name} Packages"
    href: "url:/packages/{technology.slug}"
  - label: "{package.name}"

card:
  topic: data
  icon: switch
  tags: enum enums native enum backed enum unit enum trait labels options names values case checks isActive php81
  description: One trait that adds framework-independent helpers to native PHP enums, such as names, values, labels, options, lookup by case name, strict comparisons and isActive() style case checks, without replacing cases(), from() or tryFrom().

seo:
  title: "{package.fullName} - Helpers for Native PHP Enums"
  description: "{package.name} is a PHP package that adds names, values, labels, options, name lookup, comparisons and is<Case>() checks to native PHP enums through a single trait. {package.downloadsShort}+ downloads, {package.license} licensed."
  keywords: php enum, php enum helpers, native enum, backed enum, unit enum, enum labels, enum options, enum case checks, enum trait, php 8.1 enum
  author: Pharaonic
  images:
    - "{package.cover}"
  openGraph:
    type: website
    siteName: Pharaonic
  twitter:
    card: summary_large_image

schema:
  "@type": SoftwareSourceCode
  name: "{package.name}"
  description: "{package.name} is a PHP package that adds names, values, labels, options, name lookup, comparisons and is<Case>() checks to native PHP enums through a single trait."
  image: "{package.cover}"
  codeRepository: "{package.githubUrl}"
  programmingLanguage: PHP
  runtimePlatform: "{technology.name}"
  version: "{package.version}"
  datePublished: "{package.publishedAt}"
  dateModified: "{package.updatedAt}"
  license: "https://opensource.org/licenses/{package.license}"
  isAccessibleForFree: true
  sameAs:
    - "{package.githubUrl}"
    - "{package.packagistUrl}"
  author:
    "@id": url:/#organization
  publisher:
    "@id": url:/#organization
  interactionStatistic:
    "@type": InteractionCounter
    interactionType: https://schema.org/DownloadAction
    userInteractionCount: "{package.downloads}"
---
