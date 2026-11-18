# Docsidian

Docsidian is a package that publishes your Obsidian vault to a Laravel site. Here are our favorite use cases for it:

* Documentation Site
* Blog
* Knowledge Base
* Simply publish your vault

## Installation

`composer require artisan-build/docsidian`

## Configuration

## Memberware

This package is part of our internal toolkit and is optimized for our own purposes. We do not accept issues or PRs
in this repository. 


## Archived 2026-11-18

This repository is archived and read-only. Abandoned, and its gate was independently broken: the `lint` step runs `vendor/bin/pint` while `laravel/pint` is absent from `require-dev`, so `composer ready` exits 127 on a clean checkout, with 2 larastan errors and 4 test failures behind that.

Its open "Drop PHP 8.4 from CI" pull request (#7) was closed unmerged at the same time. That PR was
not what broke CI here: `orchestra/testbench ^9.x` accepts only `laravel/framework` 11.x, and every
11.x release is now withheld by Composer's security-advisory policy, so `composer install` fails
before a test runs. The full diagnosis of all twenty repos in that sweep is in brain at
`projects/brain/reports/drop-php84-prs-2026-11-10.md`.
