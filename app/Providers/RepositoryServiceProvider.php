<?php

namespace App\Providers;

use App\Models\User;
use App\Repository\UserRepository;
use App\Repository\UserRepositoryEloquent;
use Carbon\Laravel\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepository::class,function(){
            return new UserRepositoryEloquent(new User());
        });
    }
}
