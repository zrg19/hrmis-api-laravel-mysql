<?php

namespace App\Providers;

use Tymon\JWTAuth\Providers\LaravelServiceProvider;
use Tymon\JWTAuth\Claims\Factory as ClaimFactory;
use Illuminate\Http\Request;

class JwtServiceProvider extends LaravelServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
        
        // Override the claim factory to fix Carbon 3.x compatibility
        $this->app->singleton('tymon.jwt.claim.factory', function ($app) {
            $factory = new class($app[Request::class]) extends ClaimFactory {
                public function setTTL($ttl)
                {
                    // Ensure TTL is always an integer for Carbon 3.x compatibility
                    $this->ttl = (int) $ttl;
                    return $this;
                }
            };
            
            return $factory;
        });
    }
}
