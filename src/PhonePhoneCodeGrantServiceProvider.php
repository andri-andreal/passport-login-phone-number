<?php

namespace Andreal\PhoneNumberCodeGrant;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use Andreal\PhoneNumberCodeGrant\Bridge\UserRepository;

class PhoneNumberCodeGrantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->runningInConsole() || $this->app->runningUnitTests()) {
            $this->registerGrantType();
        }
    }

    protected function registerGrantType()
    {
        $this->app
            ->make(AuthorizationServer::class)
            ->enableGrantType($this->makeNumberVerificationCodeGrant(), Passport::tokensExpireIn());
    }

    protected function makeNumberVerificationCodeGrant()
    {
        $grant = new PhoneVerificationCodeGrant(
            $this->app->make(UserRepository::class),
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
