Laravel-Safepass for Laravel 5
=============================

This package allows you to check the given password based on
[Zxcvbn](https://github.com/bjeavons/zxcvbn-php/) and use it to validate its 
strength / entropy.

> Note: Depending on how heavy the load on your application is, it might be wiser
> to use something else as the checks can be quite expensive on computing time.

## The why

I got tired of solutions using some arbitrary regex to validate that the password
contains at least one uppercase character, lowercase character, digit etc.
Those requirements are __not__ safe, not to mention that they advocate the exact opposite
of what you were trying to accomplish.

See: [xkcd](https://xkcd.com/936/) or [codinghorror](https://blog.codinghorror.com/password-rules-are-bullshit/)
for explanations.

This package uses - as mentioned above - https://github.com/bjeavons/zxcvbn-php/
as a means to calculate the passwords entropy and estimated cracking time.
It will then go ahead and convert that value to a percentage in
order to make writing rules more convenient.

The percentage is based off 10^8 seconds.
 - So 100% is ~ 3 years,
 - 50% would be ~ 1.5 years,
 - 10% would be ~ 115 days etc.

The default value is 50%.

## Installation

Require via composer:
```
composer require lukasjankowski/laravel-safepass
```

Include the service provider within your ``` config/app.php ```.
```php
'providers' => [
    // ...
    LukasJankowski\SafePass\SafePassServiceProvider::class
];
```

## Usage

Simply add the ```safepass``` as a rule to your request validation.

Examples:
```php
 public function create(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|min:4',
                'password' => 'required|safepass',
            ]
        );
        
        return 'Created.';
    }
```

If you want to override the standard of 50% you can add a parameter to the rule:
```php
 public function create(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|min:4',
                'password' => 'required|safepass:100', // In percent
            ]
        );
        
        return 'Created.';
    }
```

The default error message is:
```php
    'safepass' => 'The password you entered is easily guessable. Please use a more complex one.'
```
which you can override just like you would with other rules.

## TODO
 - Unit tests