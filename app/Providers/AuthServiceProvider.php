<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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
    //
  ];

  /**
   * Register any authentication / authorization services.
   */
  public function boot(): void
  {
    Gate::define("customer", function ($user): bool {
      return (bool) $user->isCustomer();
    });

    Gate::define("admin", function ($user): bool {
      return (bool) $user->isAdmin();
    });

    Gate::define("view admins", function ($user) {
      return $user->permissions()->pluck("name")->contains("view admins");
    });
    Gate::define("create admins", function ($user) {
      return $user->permissions()->pluck("name")->contains("create admins");
    });
    Gate::define("edit admins", function ($user) {
      return $user->permissions()->pluck("name")->contains("edit admins");
    });
    Gate::define("delete admins", function ($user) {
      return $user->permissions()->pluck("name")->contains("delete admins");
    });
    Gate::define("force delete admins", function ($user) {
      return $user->permissions()->pluck("name")->contains("force delete admins");
    });
    Gate::define("assign role", function ($user) {
      return $user->permissions()->pluck("name")->contains("assign role");
    });

    Gate::define("view products", function ($user) {
      return $user->permissions()->pluck("name")->contains("view products");
    });
    Gate::define("create products", function ($user) {
      return $user->permissions()->pluck("name")->contains("create products");
    });
    Gate::define("edit products", function ($user) {
      return $user->permissions()->pluck("name")->contains("edit products");
    });
    Gate::define("delete products", function ($user) {
      return $user->permissions()->pluck("name")->contains("delete products");
    });
    Gate::define("force delete products", function ($user) {
      return $user->permissions()->pluck("name")->contains("force delete products");
    });

    Gate::define("view categories", function ($user) {
      return $user->permissions()->pluck("name")->contains("view categories");
    });
    Gate::define("create categories", function ($user) {
      return $user->permissions()->pluck("name")->contains("create categories");
    });
    Gate::define("edit categories", function ($user) {
      return $user->permissions()->pluck("name")->contains("edit categories");
    });
    Gate::define("delete categories", function ($user) {
      return $user->permissions()->pluck("name")->contains("delete categories");
    });
    Gate::define("force delete categories", function ($user) {
      return $user->permissions()->pluck("name")->contains("force delete categories");
    });

    Gate::define("view brands", function ($user) {
      return $user->permissions()->pluck("name")->contains("view brands");
    });
    Gate::define("create brands", function ($user) {
      return $user->permissions()->pluck("name")->contains("create brands");
    });
    Gate::define("edit brands", function ($user) {
      return $user->permissions()->pluck("name")->contains("edit brands");
    });
    Gate::define("delete brands", function ($user) {
      return $user->permissions()->pluck("name")->contains("delete brands");
    });
    Gate::define("force delete brands", function ($user) {
      return $user->permissions()->pluck("name")->contains("force delete brands");
    });

    Gate::define("view customers", function ($user) {
      return $user->permissions()->pluck("name")->contains("view customers");
    });
    Gate::define("delete customers", function ($user) {
      return $user->permissions()->pluck("name")->contains("delete customers");
    });
    Gate::define("force delete customers", function ($user) {
      return $user->permissions()->pluck("name")->contains("force delete customers");
    });

    Gate::define("view orders", function ($user) {
      return $user->permissions()->pluck("name")->contains("view orders");
    });
    Gate::define("edit orders", function ($user) {
      return $user->permissions()->pluck("name")->contains("edit orders");
    });

    Gate::define("view reviews", function ($user) {
      return $user->permissions()->pluck("name")->contains("view reviews");
    });
    Gate::define("edit reviews", function ($user) {
      return $user->permissions()->pluck("name")->contains("edit reviews");
    });
    Gate::define("delete reviews", function ($user) {
      return $user->permissions()->pluck("name")->contains("delete reviews");
    });
    Gate::define("force delete reviews", function ($user) {
      return $user->permissions()->pluck("name")->contains("force delete reviews");
    });


  }
}
