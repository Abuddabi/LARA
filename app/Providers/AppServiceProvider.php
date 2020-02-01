<?php

namespace App\Providers;

use App\Category;
use App\Post;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
      Schema::defaultStringLength(191);
      view()->composer('pages._sidebar', function($view){
        $view->with('popularPosts', Post::getPopularPosts());
        $view->with('featuredPosts', Post::getFeaturedPOSts());
        $view->with('recentPosts', Post::getRecentPosts());
        $view->with('categories', Category::all());
      });
    }
}
