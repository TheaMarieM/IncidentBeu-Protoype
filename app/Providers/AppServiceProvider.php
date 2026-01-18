<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\Student;
use App\Models\Incident;
use App\Models\ParentModel;
use App\Policies\StudentPolicy;
use App\Policies\IncidentPolicy;
use App\Policies\ParentPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Register policies
        Gate::policy(Student::class, StudentPolicy::class);
        Gate::policy(Incident::class, IncidentPolicy::class);
        Gate::policy(ParentModel::class, ParentPolicy::class);
    }
}
