<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Certificate;
use App\Policies\CertificatePolicy;
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
        Certificate::class => CertificatePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

         Gate::define('view-dashboard', function ($user) {
            return $user->isAdmin();
        });
    }
}
