# Laravel Tickets

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rexlmanu/laravel-tickets.svg?style=flat-square)](https://packagist.org/packages/rexlmanu/laravel-tickets)
[![Build Status](https://img.shields.io/travis/rexlmanu/laravel-tickets/master.svg?style=flat-square)](https://travis-ci.org/rexlmanu/laravel-tickets)
[![Quality Score](https://img.shields.io/scrutinizer/g/rexlmanu/laravel-tickets.svg?style=flat-square)](https://scrutinizer-ci.com/g/rexlmanu/laravel-tickets)
[![Total Downloads](https://img.shields.io/packagist/dt/rexlmanu/laravel-tickets.svg?style=flat-square)](https://packagist.org/packages/rexlmanu/laravel-tickets)

Simple but effective solution to provide support. Due to its lightweight construction, it fits into any project. In addition, it offers a wide range of configuration options from the start and is therefore suitable for any area.

## Features

- Highly configurable
- auto closing
- file upload support
- permission support
- easy to customize

## Preview

Ticket list:
![ticket list](.github/images/image1-d4as.png)
Ticket creation:
![ticket create](.github/images/image2-d4as.png)
Ticket show:
![ticket show](.github/images/image3-d4as.png)

## Todos

- model reference support
- tailwind and vue frontend
- admin ticket scaffold
- unit tests

## Installation

You can install the package via composer:

```bash
composer require rexlmanu/laravel-tickets
```

After the installation
```bash
php artisan vendor:publish --provider=RexlManu\LaravelTickets\LaravelTicketsServiceProvider
```

## Documentation

Currently the views are only implemented for bootstrap. After publishing, you should implement the layouts.

The trait ``HasTickets`` should be added to the user model
```php
class User
{
    use \RexlManu\LaravelTickets\Traits\HasTickets; // important for laravel-tickets
}
```

Config: All points of the configuration are documented.

### Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email rexlmanude@gmail.com instead of using the issue tracker.

## Credits

- [Emmanuel Lampe](https://github.com/rexlmanu)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
