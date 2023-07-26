<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use App\Models\EmailSetting;
use Illuminate\Support\ServiceProvider;
use Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $settings=EmailSetting::first();
        // config(['mail.mailers.smtp.host' => $settings->mail_host]);
        // config(['mail.mailers.smtp.driver' => $settings->mail_driver]);
        // config(['mail.mailers.smtp.encryption' => $settings->mail_encription]);
        // config(['mail.mailers.smtp.username' => $settings->mail_username]);
        // config(['mail.mailers.smtp.password' => $settings->mail_password]);
        // config(['mail.from.address' => $settings->mail_from]);
    }
}
