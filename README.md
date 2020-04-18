<p align="center">   
    <a href="https://github.com/raditzfarhan/laravel-user-security"><img src="https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square" alt="License"></a>
    <a href="https://github.com/raditzfarhan/laravel-user-security"><img src="https://github.styleci.io/repos/7548986/shield?style=square" alt="styleci"></img></a>
</p>

# Laravel User Security - RFAuthenticator

Add security pin, mnemonic key and 2fa authentication feature to users.

## Installation

Via Composer

``` bash
$ composer require raditzfarhan/laravel-user-security:^1.0
```

## Configuration

The Laravel and Lumen configurations vary slightly, so here are the instructions for each of the frameworks.

### Laravel

Edit the `config/app.php` file and add the following line to register the service provider:

```php
'providers' => [
    ...
    RaditzFarhan\UserSecurity\UserSecurityServiceProvider::class,
    ...
],
```

> Tip: If you're on Laravel version **5.5** or higher, you can skip this part of the setup in favour of the Auto-Discovery feature.

### Lumen

Edit the `bootstrap/app.php` file and add the following line to register the service provider:

```php
...
$app->register(RaditzFarhan\UserSecurity\UserSecurityServiceProvider::class);
...
```

You will also need to enable `Facades`  in `bootstrap/app.php`:

```php
..
$app->withFacades(true, [
    ...
    RaditzFarhan\UserSecurity\Facades\RFAuthenticator::class => 'RFAuthenticator'
]);
...
```

Open your user provider model class, for example `App\User`, and add `RaditzFarhan\UserSecurity\Traits\UserSecurable` trait:

```php
<?php

namespace App;

...
use RaditzFarhan\UserSecurity\Traits\UserSecurable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    ...
    use UserSecurable;
    ...
}
```

## Usage

Example usage as below snippet:

```php
// to add/update security pin for eloquent user
$user->updateSecurityPin($security_pin);

// to add/update entropy for eloquent user
$user->updateEntropy($entropy);

// to add/update multiple authenticators
$user->updateMultipleAuthenticators(['security_pin' => $security_pin, 'mnemonic_entropy' => $entropy]);
```

To use mnemonic functions, examples as below:

```php
// Success response

// using service container to generate mnemonic object
$mnemonic = app('RFAuthenticator')->mnemonic()->generate();

// using alias to generate mnemonic object
$mnemonic = \RFAuthenticator::mnemonic()->generate();

// Use mnemonic codes to find entropy
$mnemonic = \RFAuthenticator::mnemonic()->words($words);

// Generate Mnemonic using specified Entropy
$mnemonic = \RFAuthenticator::mnemonic()->entropy($entropy);
```

## Change log

Please see the [changelog](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Raditz Farhan](https://github.com/raditzfarhan)

## License

MIT. Please see the [license file](LICENSE) for more information.