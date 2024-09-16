# nova-idle
Nova 4 package to detect idle/non active user, notice and autologout

![imagen](https://github.com/user-attachments/assets/c05c580f-6121-49cf-9b13-9f7396d641bd)

## Installation

`composer require eom-plus/nova-idle`

## Usage

When you install this package it's automaticcaly injected to nova.


## Configuration

To be able to config timers, you  must publish config file

```
php artisan vendor:publish --provider="EomPlus\NovaIdle\ToolServiceProvider" --tag=config
```

You must tune 'duration' and 'reminders' values. 
By default we start detect idle during 5mn and show dialog when remain 60 seconds.

```php
<?php

return [
  'enabled' => true,
  'debug' => false,
  'duration' => 60 * 5,
  'reminders' => [
     60
   ],
];
```

### Translations

Currenlty we have include  spanish and english translations.
If you want to add more language you publish  translations with the following command

```
php artisan vendor:publish --provider="EomPlus\NovaIdle\ToolServiceProvider" --tag=translations
```

If you add more languages don't hesitate to send us a PR.

## ⭐️ Show Your Support

Please give a ⭐️ if this project helped you!

### Other Packages You Might Like

- [Nova Rating Field](https://github.com/operativeit/nova-rating-field) - A Star rating Nova 4 field to use in your Laravel Nova apps.
- [Nova Feedback Field](https://github.com/operativeit/nova-feedback-field) - An Emoji feedback Nova 4 field to use in your Laravel Nova apps.
- [Nova Input Group](https://github.com/operativeit/nova-input-group) - A Laravel Nova 4 text field formatted as input group
- [Nova Signature](https://github.com/operativeit/nova-signature) - A Laravel Nova 4 signature pad
 
Take a look to our Github repositories as we have a lot of forked nova components with fixes that are still not merge into main owner branch.

## License

The MIT License (MIT). Please see [License File](h
