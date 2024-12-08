<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Produit;
use App\Policies\ProduitPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Produit::class => ProduitPolicy::class,
    ];

    /**
     * Register any authentication / authorization service.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
