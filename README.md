# A markdown documentation site generator optimized for Obsidian

[![Latest Version on Packagist](https://img.shields.io/packagist/v/artisan-build/docsidian.svg?style=flat-square)](https://packagist.org/packages/artisan-build/docsidian)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/artisan-build/docsidian/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/artisan-build/docsidian/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/artisan-build/docsidian/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/artisan-build/docsidian/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/artisan-build/docsidian.svg?style=flat-square)](https://packagist.org/packages/artisan-build/docsidian)

Docsidian is an opinionated Laravel-based documentation site generator, optimized for how we use Obsidian. It allows us
to simply create a git repository in any folder of our Obsidian vault to create docs for a project or mount a folder in
any project's documentation folder as an Obsidian vault and just build.

### Not Ready For You To Use

This is still in very early development. We're dogfooding it on a few projects already, but it is going to change
quickly for a while and will definitely break. We will make an announcement when it's ready.

## Docsidian is Part of Scalpels


## Installation

You can install the package via composer:

```bash
composer require artisan-build/docsidian
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="docsidian-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="docsidian-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="docsidian-views"
```

## Usage

```php
$docsidian = new ArtisanBuild\Docsidian();
echo $docsidian->echoPhrase('Hello, ArtisanBuild!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ed Grosvenor](https://github.com/edgrosvenor)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
