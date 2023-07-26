<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is_admin', function (User $user) {
            return $user->type === config('constants.USER_TYPE.ADMIN');
        });

        Gate::define('is_teacher', function (User $user) {
            return $user->type === config('constants.USER_TYPE.TEACHER');
        });

        Gate::define('is_student', function (User $user) {
            return $user->type === config('constants.USER_TYPE.STUDENT');
        });
    }
}
