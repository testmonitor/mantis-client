# TestMonitor Mantis Client

[![Latest Stable Version](https://poser.pugx.org/testmonitor/mantis-client/v/stable)](https://packagist.org/packages/testmonitor/mantis-client)
[![CircleCI](https://img.shields.io/circleci/project/github/testmonitor/mantis-client.svg)](https://circleci.com/gh/testmonitor/mantis-client)
[![StyleCI](https://styleci.io/repos/223800227/shield)](https://styleci.io/repos/223800227)
[![codecov](https://codecov.io/gh/testmonitor/mantis-client/graph/badge.svg?token=KJXOGDF7SJ)](https://codecov.io/gh/testmonitor/mantis-client)
[![License](https://poser.pugx.org/testmonitor/mantis-client/license)](https://packagist.org/packages/testmonitor/mantis-client)

This package provides a very basic, convenient, and unified wrapper for the [Mantis REST API](https://documenter.getpostman.com/view/29959/mantis-bug-tracker-rest-api/7Lt6zkP?version=latest).

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Examples](#examples)
- [Tests](#tests)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

To install the client you need to require the package using composer:

	$ composer require testmonitor/mantis-client

Use composer's autoload:

```php
require __DIR__.'/../vendor/autoload.php';
```

You're all set up now!

## Usage

You'll have to instantiate the client using your credentials:

```php
$mantis = new \TestMonitor\Mantis\Client('https://instance-name.mantishub.io', 'REST token');
```

Next, you can start interacting with Mantis.

## Examples

Get a list of Mantis projects:

```php
$projects = $mantis->projects();
```

Or creating an issue, for example (using category 'Bug' and project 1):

```php
$issue = $mantis->createIssue(new \TestMonitor\Mantis\Resources\Issue([
    'summary' => 'Some issue',
    'description' => 'A better description',
    'category' => 'Bug',
]), '1');
```

## Tests

The package contains integration tests. You can run them using PHPUnit.

    $ vendor/bin/phpunit

## Changelog

Refer to [CHANGELOG](CHANGELOG.md) for more information.

## Contributing

Refer to [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

## Credits

* **Thijs Kok** - *Lead developer* - [ThijsKok](https://github.com/thijskok)
* **Stephan Grootveld** - *Developer* - [Stefanius](https://github.com/stefanius)
* **Frank Keulen** - *Developer* - [FrankIsGek](https://github.com/frankisgek)
* **Muriel Nooder** - *Developer* - [ThaNoodle](https://github.com/thanoodle)

## License

The MIT License (MIT). Refer to the [License](LICENSE.md) for more information.
