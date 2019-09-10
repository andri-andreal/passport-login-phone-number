# Larave Passport with PhoneNumber Verification

## Installation

```bash
composer require andri-andreal/passport-login-phone-number
```

## Register to Laravel/Lumen



### Laravel
in config/app.php

`\Andreal\PhoneNumberCodeGrant\PhoneNumberCodeGrantServiceProvider::class`



```php
'providers' => [
    /*
     * Package Service Providers...
     */
     ...
     \Andreal\PhoneNumberCodeGrant\PhoneNumberCodeGrantServiceProvider::class,
]
```

## Lumen

add in `bootstrap/app.php`

```php
$app->register(\Andreal\PhoneNumberCodeGrant\PhoneNumberCodeGrantServiceProvider::class);
```

# How To Use？

## Add Interface

1. in `User` Model add `Andreal\PhoneNumberCodeGrant\Interfaces\PhoneNumberCodeGrantUserInterface` 

```php
<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Andreal\PhoneNumberCodeGrant\Interfaces\PhoneNumberCodeGrantUserInterface;

class User extends Authenticatable implement PhoneVerificationCodeGrantUserInterface
{
    use HasApiTokens, Notifiable;
}
```

2. in `User` Model`findOrNewForPassportVerifyCodeGrant` add `validateForPassportVerifyCodeGrant` function

```php
/**
 * Find or create a user by phone number
 *
 * @param $phoneNumber
 * @return \Illuminate\Database\Eloquent\Model|null
 */
public function findOrCreateForPassportVerifyCodeGrant($phoneNumber)
{
    // If you need to automatically register the user.
    return static::firstOrCreate(['mobile' => $phoneNumber]);

    // If the phone number is not exists in users table, will be fail to authenticate.
    // return static::where('mobile', '=', $phoneNumber)->first();
}

/**
 * Check the verification code is valid.
 *
 * @param $verificationCode
 * @return boolean
 */
public function validateForPassportVerifyCodeGrant($verificationCode)
{
    // Check verification code is valid.
    // return \App\Code::where('mobile', $this->mobile)->where('code', '=', $verificationCode)->where('expired_at', '>', now()->toDatetimeString())->exists();
    return true;
}
```


## Get Token

Use `POST`tp Endpoint `/oautn/token` 

```php
$http = new GuzzleHttp\Client;

$response = $http->post('http://your-app.com/oauth/token', [
    'form_params' => [
        'grant_type' => 'phone_verification_code',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'phone_number' => '+8613416292625',
        'verification_code' => 927068,
        'scope' => '*',
    ],
]);

return json_decode((string) $response->getBody(), true);
```