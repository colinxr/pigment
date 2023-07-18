<?php

namespace App\Providers;

use App\Services\GoogleApiService;
use Illuminate\Support\ServiceProvider;
use App\Exceptions\InvalidGCalConfiguration;
use App\Interfaces\GoogleApiServiceInterface;
use Illuminate\Contracts\Foundation\Application;

class GoogleApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GoogleApiServiceInterface::class, function (Application $app) {
            $config = config('google-client');

            $this->guardAgainstInvalidConfiguration($config);
            return new GoogleApiService($config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    protected function guardAgainstInvalidConfiguration(array $config = null)
    {
        if (app()->environment('testing')) return;

        if (!$config) throw InvalidGCalConfiguration::credentialsJsonDoesNotExist('');

        $authProfile = $config['default_auth_profile'];

        if ($authProfile === 'service_account') {
            $this->validateServiceAccountConfigSettings($config);

            return;
        }

        if ($authProfile === 'oauth') {
            $this->validateOAuthConfigSettings($config);

            return;
        }

        throw InvalidGCalConfiguration::invalidAuthenticationProfile($authProfile);
    }

    protected function validateServiceAccountConfigSettings(array $config = null)
    {
        $credentials = $config['auth_profiles']['service_account']['credentials_json'];

        $this->validateConfigSetting($credentials);
    }

    protected function validateOAuthConfigSettings(array $config = null)
    {
        $credentials = $config['auth_profiles']['oauth']['credentials_json'];

        $this->validateConfigSetting($credentials);

        // $token = $config['auth_profiles']['oauth']['token_json'];

        // $this->validateConfigSetting($token);
    }

    protected function validateConfigSetting(string $setting)
    {
        if (!is_array($setting) && !is_string($setting)) {
            throw InvalidGCalConfiguration::credentialsTypeWrong($setting);
        }

        if (is_string($setting) && !file_exists($setting)) {
            throw InvalidGCalConfiguration::credentialsJsonDoesNotExist($setting);
        }
    }
}
