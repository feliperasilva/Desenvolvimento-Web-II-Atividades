<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\Author;

use App\Policies\BookPolicy;
use App\Policies\PublisherPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\AuthorPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Book::class => BookPolicy::class,
        Publisher::class => PublisherPolicy::class,
        Category::class => CategoryPolicy::class,
        Author::class => AuthorPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
