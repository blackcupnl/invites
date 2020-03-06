# Laravel Invites

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

This package allows you to send out custom invites. Once an invite has been sent, the recipient receives a Notification with the option to accept or reject the invite. The sender receives a notification when the recipient accepts or rejects the invite. Every invite has a dedicated class to customize the actions that should be performed once the intive is sent, accepted or rejected.

## Installation

Via Composer

``` bash
composer require blackcup/invites
```
The package will automatically register itself. Run the provided migrations using
``` bash
php artisan migrate
```


## Example usage

Create a new `MakeAdminInvite` by running
``` bash
php artisan make:invite MakeAdminInvite
```
This will create a new class in `app\Invites`. Change the code as follows:
``` php
<?php

namespace App\Invites;

use BlackCup\Invites\Invite;
use Illuminate\Support\Facades\Auth;

class MakeAdminInvite extends Invite
{
    /**
     * Indicates if accepting the Invite can only be done by an authenticated user.
     *
     * @var bool
     */
    protected $accept_requires_authentication = true;

    /**
     * Execute Invite specific code on accept.
     *
     * @return void
     */
    public function accept()
    {
        $user = Auth::user();
        $user->admin = true;
        $user->save();
    }
}
```
Once this invite has been accepted by the recipient, the current user's `admin` field will be set to `true`.

Next we can send the invite using
``` php
$invite = new MakeAdminInvite();
Invites::send($invite, 'We would like to make you admin to our awesome site', 'James Recipient', 'recipient@example.com', 'John Sender', 'sender@example.com');
```

## Resources
Package resources can be published using:
``` bash
php artisan vendor:publish --provider="BlackCup\InvitesServiceProvider" [--tag="..."]
```
(possible tags are `config`, `views`, `lang`)

## Customization
If you don't want to use the default routes, set the boolean `routes` to `false` in `config/invites.php`.

### Inside your custom Invite class you can override several protected properties to change the behaviour of the Invite:

You can change the notifications to be sent at several steps in the Invite process. Set property to `null` to disable a notification entirely.
``` php
/**
 * The Notification to send to the recipient of the invite.
 *
 * @var string|null Notification class
 */
protected $received_notification = InvitedNotification::class;

/**
 * The Notification to send to the sender when the invite has been accepted.
 *
 * @var string|null Notification class
 */
protected $accepted_notification = InviteAcceptedNotification::class;

/**
 * The Notification to send to the sender when the invite has been rejected.
 *
 * @var string|null Notification class
 */
protected $rejected_notification = InviteRejectedNotification::class;
```


To require the user to be authenticated before accepting or rejecting an invite, override the following properties:
``` php
/**
 * Indicates if accepting the Invite can only be done by an authenticated user.
 *
 * @var bool
 */
protected $accept_requires_authentication = true;

/**
 * Indicates if rejecting the Invite can only be done by an authenticated user.
 *
 * @var bool
 */
protected $reject_requires_authentication = true;
```

To set a generic text describing your invite:
``` php
/**
 * Description of the invite.
 *
 * @var string|null
 */
protected $description = '...';
```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
vendor/bin/phpunit
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email info@blackcup.nl instead of using the issue tracker.

## Credits

- [BlackCup][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/blackcup/invites.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/blackcup/invites.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/blackcup/invites/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/blackcup/invites
[link-downloads]: https://packagist.org/packages/blackcup/invites
[link-author]: https://github.com/blackcup
[link-contributors]: ../../contributors
