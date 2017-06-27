<?php

namespace App\Providers;

use App\Auth\UserProvider;
use App\Repositories\OAuth\AccessTokenRepository;
use App\Repositories\OAuth\ClientRepository;
use App\Repositories\OAuth\RefreshTokenRepository;
use App\Repositories\OAuth\ScopeRepository;
use App\Repositories\UserRepository;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use LaravelDoctrine\ORM\Auth\DoctrineUserProvider;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->extendAuthManager();
        $this->registerOAuthServer();
    }

    protected function extendAuthManager()
    {
        $this->app->make('auth')->provider('doctrine-argon2i', function ($app, $config) {
            $entity = $config['model'];

            $em = $app['registry']->getManagerForClass($entity);

            if (!$em) {
                throw new InvalidArgumentException("No EntityManager is set-up for {$entity}");
            }

            return new UserProvider(
                $app['hash'],
                $em,
                $entity
            );
        });
    }

    private function registerOAuthServer()
    {
        $this->app->bind(AuthorizationServer::class, function(Application $app) {
            $fileManager = $app->make(FilesystemManager::class);

            // Setup the authorization server
            $server = new AuthorizationServer(
                $app->make(ClientRepository::class),      // instance of ClientRepositoryInterface
                $app->make(AccessTokenRepository::class), // instance of AccessTokenRepositoryInterface
                $app->make(ScopeRepository::class),       // instance of ScopeRepositoryInterface
                $fileManager->drive()->get('keys/oauth.key'),      // path to private key
                $fileManager->drive()->get('keys/oauth.pub')       // path to public key
            );

            $grant = new PasswordGrant(
                $app->make(UserRepository::class),           // instance of UserRepositoryInterface
                $app->make(RefreshTokenRepository::class)    // instance of RefreshTokenRepositoryInterface
            );
            $grant->setRefreshTokenTTL(new \DateInterval('P1M')); // refresh tokens will expire after 1 month

            // Enable the password grant on the server with a token TTL of 1 hour
            $server->enableGrantType(
                $grant,
                new \DateInterval('PT1H') // access tokens will expire after 1 hour
            );

            return $server;
        });
    }
}
