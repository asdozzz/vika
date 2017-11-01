<?php
namespace Asdozzz\{{$module|ucfirst}};

use Illuminate\Support\ServiceProvider;

class {{$essence|ucfirst}}ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }
}